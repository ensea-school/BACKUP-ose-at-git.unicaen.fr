<?php

namespace ExportRh\Connecteur\Siham;

use Contrat\Service\ContratServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use ExportRh\Connecteur\ConnecteurRhInterface;
use ExportRh\Entity\IntervenantRh;
use ExportRh\Form\Fieldset\SihamFieldset;
use ExportRh\Service\ExportRhServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\SituationMatrimonialeServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Service\TauxRemuServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Form\Fieldset;
use Lieu\Service\AdresseNumeroComplServiceAwareTrait;
use Lieu\Service\VoirieServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenSiham\Exception\SihamException;
use UnicaenSiham\Service\Siham;

class SihamConnecteur implements ConnecteurRhInterface
{
    use EntityManagerAwareTrait;
    use DossierServiceAwareTrait;
    use ExportRhServiceAwareTrait;
    use AdresseNumeroComplServiceAwareTrait;
    use VoirieServiceAwareTrait;
    use ContratServiceAwareTrait;
    use SituationMatrimonialeServiceAwareTrait;
    use TauxRemuServiceAwareTrait;

    public Siham $siham;



    public function __construct(Siham $siham)
    {
        $this->siham = $siham;
    }



    public function rechercherIntervenantRh($nomUsuel = '', $prenom = '', $insee = ''): array
    {
        $params = [
            'nomUsuel'    => $nomUsuel,
            'prenom'      => $prenom,
            'numeroInsee' => $insee,

        ];

        $listIntervenantRh = [];
        $result            = $this->siham->recupererListeAgents($params);

        if (!empty($result)) {
            foreach ($result as $v) {
                $intervenantRh = new IntervenantRh();
                $intervenantRh->setNomUsuel($v->getNomUsuel());
                $intervenantRh->setPrenom($v->getPrenom());
                $intervenantRh->setCodeRh($v->getMatricule());
                $dateNaissance = new \DateTime($v->getDateNaissance());
                $intervenantRh->setDateNaissance($dateNaissance);
                $intervenantRh->setNumeroInsee($v->getNumeroInseeDefinitif());
                $listIntervenantRh[] = $intervenantRh;
            }
        }

        return $listIntervenantRh;
    }



    public function recupererAffectationEnCoursIntervenantRh(Intervenant $intervenant): ?array
    {
        $typeAffectation         = (isset($this->siham->getConfig()['type-affectation'])) ? $this->siham->getConfig()['type-affectation'] : ['FUN'];
        $affectations            = [];
        $donneesAdministratives  = $this->recupererDonneesAdministrativesIntervenantRh($intervenant);
        $anneeUniversitaireDebut = $intervenant->getAnnee()->getDateDebut();
        $anneeUniversitaireFin   = $intervenant->getAnnee()->getDateFin();

        if (!empty($donneesAdministratives['listeAffectations']) || !empty($donneesAdministratives->listeAffectations)) {
            $listeAffectations = $donneesAdministratives['listeAffectations'];
            if (is_array($listeAffectations)) {
                foreach ($listeAffectations as $affectation) {
                    if (in_array($affectation->codeTypeRattachement, $typeAffectation)) {
                        $dateDebutAffectation = new \DateTime($affectation->dateDebutAffectation);
                        $dateFinAffectation   = new \DateTime($affectation->dateFinAffectation);
                        $currentDate          = new \DateTime();
                        /*Si il y a dejà eu une affectation dans l'année universitaire en cours on la remonte afin que OSE ne propose
                        plus le renouvellement de l'intervenant. Pour un second renouvellement en cours d'année il faudra le faire manuellement*/
                        if ($anneeUniversitaireFin > $dateDebutAffectation and $anneeUniversitaireDebut < $dateFinAffectation) {
                            $affectations[] = $affectation;
                        }
                    }
                }
            } else {
                //Todo relecture de code, rendre paramètrable le codeTypeRattachement en FUN ou HIE et refaire des tests.
                if (in_array($listeAffectations->codeTypeRattachement, $typeAffectation)) {
                    $dateDebutAffectation = new \DateTime($listeAffectations->dateDebutAffectation);
                    $dateFinAffectation   = new \DateTime($listeAffectations->dateFinAffectation);
                    $currentDate          = new \DateTime();
                    /*Si il y a dejà eu une affectation dans l'année universitaire en cours on la remonte afin que OSE ne propose
                        plus le renouvellement de l'intervenant. Pour un second renouvellement en cours d'année il faudra le faire manuellement*/
                    if ($anneeUniversitaireFin > $dateDebutAffectation and $anneeUniversitaireDebut < $dateFinAffectation) {
                        $affectations[] = $listeAffectations;
                    }
                }
            }
        }

        return $affectations;
    }



    public function recupererDonneesAdministrativesIntervenantRh(Intervenant $intervenant): ?array
    {
        try {
            $codeRh = '';
            //On récupére le code RH par le INSEE
            $codeRh = $this->trouverCodeRhByInsee($intervenant);

            if (!empty($intervenant->getCodeRh()) && empty($codeRh)) {
                $codeRh = $intervenant->getCodeRh();
            }

            if (!empty($codeRh)) {

                $dateObservation = $intervenant->getAnnee()->getDateDebut();
                $params          =
                    [
                        'listeMatricules'    => [$codeRh],
                        'dateObservation'    => $intervenant->getAnnee()->getDateDebut()->format('Y-m-d'),
                        'dateFinObservation' => $intervenant->getAnnee()->getDateFin()->format('Y-m-d'),
                    ];

                $donneesAdministratives = $this->siham->recupererDonneesAdministrativeAgent($params);

                return (array)$donneesAdministratives;
            }

            return null;
        } catch (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function trouverCodeRhByInsee(Intervenant $intervenant): ?string
    {
        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);
        $numeroInsee        = (!empty($intervenant->getNumeroInsee())) ? $intervenant->getNumeroInsee() : $intervenantDossier->getNumeroInsee();

        $params =
            [
                'numeroInsee' => $numeroInsee,
            ];

        $listeAgents = $this->siham->recupererListeAgents($params);
        $agent       = current($listeAgents);

        if (!empty($agent)) {
            return $agent->getMatricule();
        }

        return null;
    }



    public function recupererContratEnCoursIntervenantRh(Intervenant $intervenant): ?array
    {
        $contrats                = [];
        $donneesAdministratives  = $this->recupererDonneesAdministrativesIntervenantRh($intervenant);
        $anneeUniversitaireDebut = $intervenant->getAnnee()->getDateDebut();
        $anneeUniversitaireFin   = $intervenant->getAnnee()->getDateFin();

        if (!empty($donneesAdministratives['listeContrats']) || !empty($donneesAdministratives->listeContrats)) {
            $listeContrats = (isset($donneesAdministratives['listeContrats']) && is_array($donneesAdministratives['listeContrats'])) ? $donneesAdministratives['listeContrats'] : [$donneesAdministratives['listeContrats']];

            foreach ($listeContrats as $contrat) {

                $dateDebutContrat = new \DateTime($contrat->dateDebutContrat);
                $dateFinContrat   = new \DateTime($contrat->dateFinReelleContrat);
                $currentDate      = new \DateTime();

                if ($anneeUniversitaireFin > $dateDebutContrat and $anneeUniversitaireDebut < $dateFinContrat) {
                    $contrats[] = $contrat;
                }
            }
        };

        return $contrats;
    }



    public function prendreEnChargeIntervenantRh(Intervenant $intervenant, $datas): ?string
    {
        try {
            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /*Recherche de la date d'effet à passer selon enseignement ou mission, si mission on prend la première mission de l'année universitaire
            sinon on prend les dates de début et de fin de l'année universitaire*/
            $firstMission = $this->getServiceContrat()->getFirstContratMission($intervenant);
            $dateMission = ($this->siham->getConfig()['contrat']['missionDate'])??'MISSION';

            if (!empty($firstMission) && $dateMission == 'MISSION') {
                $dateEffet = $firstMission->getDateDebut()->format('Y-m-d');
                $dateFin   = $firstMission->getDateFin()->format('Y-m-d');
            } else {
                $anneeUniversitaire = $intervenant->getAnnee();
                $dateEffet          = $anneeUniversitaire->getDateDebut()->format('Y-m-d');
                $dateFin            = $anneeUniversitaire->getDateFin()->format('Y-m-d');
            }

            /*CARRIERE*/
            $carriere = [
                'dateEffetCarriere' => $dateEffet,
                'grade'             => '0000',
                'qualiteStatutaire' => 'N',
                'temoinValidite'    => 1,
            ];
            /*POSITION ADMINISTRATIVE*/
            $position[] =
                ['dateEffetPosition' => $dateEffet,
                 'dateFinPrevue'     => $dateFin,
                 'dateFinReelle'     => $dateFin,
                 'position'          => $datas['connecteurForm']['position'],
                 'temoinValidite'    => 1,
                ];


            /*CONTRAT*/
            //On récupére le nombre d'heures du contrat et le taux horaire appliqué
            $infos = $this->getInfosContrat($intervenant, $firstMission);
            $config = $this->siham->getConfig();
            if ($this->siham->getConfig()['contrat']) {
                //On récupere le gradeTG pour le contrat
                $codeStatut = $datas['connecteurForm']['statut'];
                if ($this->siham->getConfig()['contrat']['active']) {
                    $gradeTG   = $this->recupererGradeTG($codeStatut);
                    $contrat[] =
                        ['dateDebutContrat'  => $dateEffet,
                         'dateFinContrat'    => $dateFin,
                         'categorieContrat'  => isset($this->siham->getConfig()['contrat']['parameters']['categorieContrat']) ? $this->siham->getConfig()['contrat']['parameters']['categorieContrat'] : '',
                         'natureContrat'     => $this->siham->getConfig()['contrat']['parameters']['natureContrat'],
                         'typeContrat'       => $this->siham->getConfig()['contrat']['parameters']['typeContrat'],
                         'typeLienJuridique' => $this->siham->getConfig()['contrat']['parameters']['typeLienJuridique'],
                         'modeRemuneration'  => $this->siham->getConfig()['contrat']['parameters']['modeRemuneration'],
                         'modeDeGestion'     => $this->siham->getConfig()['contrat']['parameters']['modeDeGestion'],
                         'gradeTG'           => $gradeTG,
                         'tauxHoraires'      => $infos['taux'],
                         'nbHeuresContrat'   => $infos['totalHeure'],
                         'temoinValidite'    => $this->siham->getConfig()['contrat']['parameters']['temoinValidite'],
                        ];
                }
            }


            /*STATUT*/
            $statut[] =
                ['dateEffetStatut' => $dateEffet,
                 'statut'          => $datas['connecteurForm']['statut'],
                 'temoinValidite'  => 1,
                ];

            /*MODALITE SERVICE*/
            $service[] =
                ['dateEffetModalite' => $dateEffet,
                 'modalite'          => $datas['connecteurForm']['modaliteService'],
                 'temoinValidite'    => 1,
                ];

            /*SITUATION FAMILIALE*/
            $situationFamiliale = [];
            if ($dossierIntervenant->getSituationMatrimoniale()) {
                $dateEffetSituationFamilliale = (!empty($dossierIntervenant->getDateSituationMatrimoniale())) ? $dossierIntervenant->getDateSituationMatrimoniale()->format('Y-m-d') : $dateEffet;
                $codeSituationFamilliale      = (!empty($dossierIntervenant->getSituationMatrimoniale())) ? $dossierIntervenant->getSituationMatrimoniale()->getCode() : 'CEL';
                $situationFamiliale[]         =
                    ['dateEffetSituFam' => $dateEffetSituationFamilliale,
                     'situFam'          => $codeSituationFamilliale,
                     'temoinValidite'   => 1,
                    ];
            }

            /*COORDONNEES POSTALES*/
            $numeroVoie = (!empty($dossierIntervenant->getAdresseNumero())) ? $dossierIntervenant->getAdresseNumero() : '';
            $natureVoie = (!empty($dossierIntervenant->getAdresseVoirie())) ? $dossierIntervenant->getAdresseVoirie()->getCodeRh() : '';
            $bisTer     = (!empty($dossierIntervenant->getAdresseNumeroCompl())) ? $dossierIntervenant->getAdresseNumeroCompl()->getCodeRh() : '';
            $nomVoie    = (!empty($dossierIntervenant->getAdresseVoie())) ? $dossierIntervenant->getAdresseVoie() : '';
            $complement = (!empty($dossierIntervenant->getAdresseLieuDit())) ? $dossierIntervenant->getAdresseLieuDit() . ' ' : ' ';
            $complement .= (!empty($dossierIntervenant->getAdressePrecisions())) ? $dossierIntervenant->getAdressePrecisions() : ' ';
            $nomVoie    = Util::stripAccents($nomVoie);
            $complement = Util::stripAccents($complement);
            $commune    = Util::stripAccents($dossierIntervenant->getAdresseCommune());
            $codePostal = $dossierIntervenant->getAdresseCodePostal();

            $coordonneesPostales[] = [
                'bureauDistributeur' => $commune,
                'bisTer'             => $bisTer,
                'natureVoie'         => $natureVoie,
                'nomVoie'            => self::cleanDatas(substr($nomVoie, 0, 28)),
                'numAdresse'         => $numeroVoie,
                'complementAdresse'  => self::cleanDatas(substr($complement, 0, 37)),
                'commune'            => $commune,
                'codePostal'         => $codePostal,
                'codePays'           => $dossierIntervenant->getAdressePays()->getCodeIso3(),
                'debutAdresse'       => $dateEffet,
            ];

            /*COORDONNEES BANCAIRES*/
            $coordonneesBancaires = [];
            if ($datas['generiqueFieldset']['iban']) {
                $coordonnees                   = $this->siham->formatCoordoonneesBancairesForSiham($dossierIntervenant->getIBAN(), $dossierIntervenant->getBIC());
                $coordonnees['dateDebBanque']  = $dateEffet;
                $coordonnees['temoinValidite'] = '1';
                $coordonnees['modePaiement']   = '25';

                $coordonneesBancaires[] = $coordonnees;
            }

            $coordonneesTelMail = [];
            if ($datas['generiqueFieldset']['telPro'] && !empty($dossierIntervenant->getTelPro())) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $dateEffet,
                    'numero'       => $dossierIntervenant->getTelPro(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO,
                ];
            }
            if ($datas['generiqueFieldset']['telPerso'] && !empty($dossierIntervenant->getTelPerso())) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $dateEffet,
                    'numero'       => $dossierIntervenant->getTelPerso(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO,
                ];
            }
            if ($datas['generiqueFieldset']['emailPro'] && !empty($dossierIntervenant->getEmailPro())) {
                //Hook temporaire en attendant correction webservice, on ne passe pas l'email pro si elle contient 'etu.unicaen' dans le nom de domaine
                // if (!preg_match('/@etu\.unicaen/', $dossierIntervenant->getEmailPro())) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $dateEffet,
                    'numero'       => $dossierIntervenant->getEmailPro(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO,
                ];
                //}
            }
            if ($datas['generiqueFieldset']['emailPerso'] && !empty($dossierIntervenant->getEmailPerso())) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $dateEffet,
                    'numero'       => $dossierIntervenant->getEmailPerso(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO,
                ];
            }

            /*NATIONALITES*/
            $nationalites[] = [
                'nationalite'   => 'FRA',//$dossierIntervenant->getPaysNationalite()->getCode(),
                'temPrincipale' => 1,
            ];

            /*PAYS NAISSANCE*/
            if (!empty($dossierIntervenant->getPaysNaissance() && !empty($dossierIntervenant->getPaysNaissance()->getCodeIso3()))) {
                $paysNaissance = $dossierIntervenant->getPaysNaissance()->getCodeIso3();
            } elseif (!empty($dossierIntervenant->getDepartementNaissance())) {
                $paysNaissance = 'FRA';
            } else {
                $paysNaissance = '';
            }

            //Traitement du départmenent
            $valueDepartement = '';
            if (!empty($dossierIntervenant->getDepartementNaissance())) {
                $departementCode = $dossierIntervenant->getDepartementNaissance()->getCode();
                if (substr($departementCode, 0, 1) == '9') {
                    $valueDepartement = $departementCode;
                } else {
                    $valueDepartement = substr($departementCode, 1, 2);
                }
            }


            $params = [
                'categorieEntree'           => 'ACTIVE',
                'civilite'                  => ($dossierIntervenant->getCivilite() == 'M.') ? '1' : '2',
                'dateEmbauche'              => $dateEffet,
                'dateNaissance'             => $dossierIntervenant->getDateNaissance()->format('Y-m-d'),
                'villeNaissance'            => self::cleanDatas($dossierIntervenant->getCommuneNaissance()),
                'departementNaissance'      => $valueDepartement,
                'paysNaissance'             => $paysNaissance,
                'emploi'                    => $datas['connecteurForm']['emploi'],
                'listeCoordonneesPostales'  => $coordonneesPostales,
                'listeCoordonneesBancaires' => $coordonneesBancaires,
                'listeCarriere'             => $carriere,
                'listeSituations'           => $situationFamiliale,
                'listeModalitesServices'    => $service,
                'listeStatuts'              => $statut,
                'listeNationalites'         => $nationalites,
                'listeNumerosTelephoneFax'  => $coordonneesTelMail,
                'listePositions'            => $position,
                'motifEntree'               => 'PEC',
                'nomPatronymique'           => self::cleanDatas($dossierIntervenant->getNomPatronymique()),
                'nomUsuel'                  => self::cleanDatas($dossierIntervenant->getNomUsuel()),
                'numeroInsee'               => (!$dossierIntervenant->getNumeroInseeProvisoire()) ? $dossierIntervenant->getNumeroInsee() : '',
                'numeroInseeProvisoire'     => ($dossierIntervenant->getNumeroInseeProvisoire()) ? $dossierIntervenant->getNumeroInsee() : '',
                'prenom'                    => self::cleanDatas($dossierIntervenant->getPrenom()),
                'sexe'                      => ($dossierIntervenant->getCivilite() == 'M.') ? '1' : '2',
                'temoinValidite'            => 1,
                'UO'                        => $datas['connecteurForm']['affectation'],
            ];


            //Si la creation du contrat via les WS est activé
            if ($this->siham->getConfig()['contrat']) {

                if ($this->siham->getConfig()['contrat']['active']) {
                    $params['listeContrats'] = $contrat;
                }
            }


            $matricule = $this->siham->priseEnChargeAgent($params);

            return $matricule;
        } catch (SihamException $e) {

            throw new \Exception($e->getMessage());
        }
    }



    public function getInfosContrat(Intervenant $intervenant, ?Mission $mission = null): array
    {
        $infos = [
            'totalHeure' => 0,
            'taux'       => 0,
        ];
        if (empty($mission)) {
            $sql = 'SELECT "hetdContrat","tauxHoraireValeur" FROM V_CONTRAT_MAIN WHERE intervenant_id = :intervenant';
            $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['intervenant' => $intervenant->getId()]);

            if (!empty($res)) {
                $infos['totalHeure'] = 0;
                $infos['taux']       = 0;
                foreach ($res as $value) {
                    $infos['totalHeure'] += (float)str_replace(',', '.', $value['hetdContrat']);
                    $infos['taux']       = $value['tauxHoraireValeur'];
                }
                $infos['totalHeure'] = str_replace(',', '.', $infos['totalHeure']);
                $infos['taux']       = str_replace(',', '.', $infos['taux']);
            }
        } else {
            $infos['totalHeure'] = str_replace(',', '.', $mission->getHeures());
            $infos['taux'] = 0;
            //On va chercher la valeur du taux de la mission
            $dateDebutMission = $mission->getDateDebut();
            $tauxRemu = $mission->getTauxRemu();
            if ($tauxRemu instanceof TauxRemu) {
                $valeurTaux    = $this->getServiceTauxRemu()->tauxValeur($tauxRemu, $dateDebutMission);
                $infos['taux'] = str_replace(',', '.', $valeurTaux);
            }
        }

        return $infos;
    }



    public function recupererGradeTG(string $codeStatut): ?string
    {
        $gradeTG = '';

        if (array_key_exists('gradeTG', $this->siham->getConfig()['contrat']['parameters'])) {
            $gradeTG = (array_key_exists($codeStatut, $this->siham->getConfig()['contrat']['parameters']['gradeTG'])) ? $this->siham->getConfig()['contrat']['parameters']['gradeTG'][$codeStatut] : '';
        }

        return $gradeTG;
    }



    public static function cleanDatas($str, $strict = false, $encoding = 'UTF-8')
    {
        if (empty($str)) {
            return $str;
        }
        $from = 'ÀÁÂÃÄÅÇÐÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜŸÑàáâãäåçðèéêëìíîïòóôõöøùúûüÿñ()…,<> /?€%!":’\'+.';
        $to   = 'AAAAAACDEEEEIIIIOOOOOOUUUUYNaaaaaacdeeeeiiiioooooouuuuyn                  ';

        $rstr = '';
        $ok   = true;
        $len  = mb_strlen($str, $encoding);
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($str, $i, 1, $encoding);
            $pos  = mb_strpos($from, $char, 0, $encoding);
            if (false === $pos) {
                if ($strict) {
                    return false;
                } else $rstr .= $char;
            } else {
                $rstr .= mb_substr($to, $pos, 1, $encoding);
            }
        }

        return $rstr;
    }



    public function renouvellerIntervenantRH(Intervenant $intervenant, $datas): ?string
    {
        try {
            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /*Recherche de la date d'effet à passer selon enseignement ou mission, si mission on prend la première mission de l'année universitaire
            sinon on prend les dates de début et de fin de l'année universitaire*/

            $firstMission = $this->getServiceContrat()->getFirstContratMission($intervenant);
            $dateMission = ($this->siham->getConfig()['contrat']['missionDate'])??'MISSION';

            if (!empty($firstMission) && $dateMission == 'MISSION') {
                $dateEffet = $firstMission->getDateDebut()->format('Y-m-d');
                $dateFin   = $firstMission->getDateFin()->format('Y-m-d');
            } else {
                $anneeUniversitaire = $intervenant->getAnnee();
                $dateEffet          = $anneeUniversitaire->getDateDebut()->format('Y-m-d');
                $dateFin            = $anneeUniversitaire->getDateFin()->format('Y-m-d');
            }

            /*Formatage du matricule*/
            //On récupére le code RH par le INSEE
            $matricule = $this->trouverCodeRhByInsee($intervenant);

            if (!empty($intervenant->getCodeRh()) && empty($matricule)) {
                $matricule = $intervenant->getCodeRh();
            }

            /*POSITION ADMINISTRATIVE*/
            $position[] =
                ['dateEffetPosition' => $dateEffet,
                 'dateFinPrevue'     => $dateFin,
                 'dateFinReelle'     => $dateFin,
                 'position'          => $datas['connecteurForm']['position'],
                 'temoinValidite'    => 1,
                ];

            /*CONTRAT*/
            //On récupére le nombre d'heures du contrat et le taux horaire appliqué
            $infos = $this->getInfosContrat($intervenant, $firstMission);
            $config  = $this->siham->getConfig();
            $contrat = [];
            if ($this->siham->getConfig()['contrat']) {
                //On récupere le gradeTG pour le contrat
                $codeStatut = $datas['connecteurForm']['statut'];
                if ($this->siham->getConfig()['contrat']['active']) {
                    $gradeTG   = $this->recupererGradeTG($codeStatut);
                    $contrat[] =
                        ['dateDebutContrat'  => $dateEffet,
                         'dateFinContrat'    => $dateFin,
                         'natureContrat'     => $this->siham->getConfig()['contrat']['parameters']['natureContrat'],
                         'typeContrat'       => $this->siham->getConfig()['contrat']['parameters']['typeContrat'],
                         'typeLienJuridique' => $this->siham->getConfig()['contrat']['parameters']['typeLienJuridique'],
                         'modeRemuneration'  => $this->siham->getConfig()['contrat']['parameters']['modeRemuneration'],
                         'modeDeGestion'     => $this->siham->getConfig()['contrat']['parameters']['modeDeGestion'],
                         'categorieContrat'  => isset($this->siham->getConfig()['contrat']['parameters']['categorieContrat']) ? $this->siham->getConfig()['contrat']['parameters']['categorieContrat'] : '',
                         'gradeTG'           => $gradeTG,
                         'tauxHoraires'      => $infos['taux'],
                         'nbHeuresContrat'   => $infos['totalHeure'],
                         'temoinValidite'    => $this->siham->getConfig()['contrat']['parameters']['temoinValidite'],

                        ];
                }
            }

            /*STATUT*/
            $statut[] =
                ['dateEffetStatut' => $dateEffet,
                 'statut'          => $datas['connecteurForm']['statut'],
                 'temoinValidite'  => 1,
                ];

            /*MODALITE SERVICE*/
            $service[] =
                ['dateEffetModalite' => $dateEffet,
                 'modalite'          => $datas['connecteurForm']['modaliteService'],
                 'temoinValidite'    => 1,

                ];

            /*CARRIERE*/
            $carriere = [
                'dateEffetCarriere' => $dateEffet,
                'grade'             => '0000',
                'qualiteStatutaire' => 'N',
                'temoinValidite'    => 1,
            ];


            $params = [
                'categorieEntree'        => 'ACTIVE',
                'dateRenouvellement'     => $dateEffet,
                'emploi'                 => $datas['connecteurForm']['emploi'],
                'listeCarriere'          => $carriere,
                'listeModalitesServices' => $service,
                'listeStatuts'           => $statut,
                'listePositions'         => $position,
                'motifEntree'            => 'REN',
                'matricule'              => $matricule,
                'temoinValidite'         => 1,
                'UO'                     => $datas['connecteurForm']['affectation'],
            ];


            //Si la creation du contrat via les WS est activé
            if ($this->siham->getConfig()['contrat']) {
                if ($this->siham->getConfig()['contrat']['active']) {
                    $params['listeContrats'] = $contrat;
                }
            }


            $matricule = $this->siham->renouvellementAgent($params);

            //Mise à jour des données personnelles de l'agent
            $this->synchroniserDonneesPersonnellesIntervenantRh($intervenant, $datas);

            return $matricule;
        } catch (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function synchroniserDonneesPersonnellesIntervenantRh(Intervenant $intervenant, $datas): bool
    {
        try {

            $intervenantRh      = $this->recupererIntervenantRh($intervenant);
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            //Synchronisation Tel pro
            if ($datas['generiqueFieldset']['telPro'] && !empty($dossierIntervenant->getTelPro())) {
                $params = [
                    'matricule' => $intervenantRh->getCodeRh(),
                    'numero'    => $dossierIntervenant->getTelPro(),
                    'dateDebut' => $intervenantRh->getTelProDateDebut(),
                ];

                $this->siham->modifierCoordonneesAgent($params, Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO);
            }

            //Synchronisation Tel perso
            if ($datas['generiqueFieldset']['telPerso'] && !empty($dossierIntervenant->getTelPerso())) {
                $params = [
                    'matricule' => $intervenantRh->getCodeRh(),
                    'numero'    => $dossierIntervenant->getTelPerso(),
                    'dateDebut' => $intervenantRh->getTelPersoDateDebut(),
                ];

                $this->siham->modifierCoordonneesAgent($params, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
            }

            //Synchronisation email pro
            if ($datas['generiqueFieldset']['emailPro'] && !empty($dossierIntervenant->getEmailPro())) {
                $params = [
                    'matricule' => $intervenantRh->getCodeRh(),
                    'numero'    => $dossierIntervenant->getEmailPro(),
                    'dateDebut' => $intervenantRh->getEmailProDateDebut(),
                ];

                $this->siham->modifierCoordonneesAgent($params, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO);
            }

            //Synchronisation email perso
            if ($datas['generiqueFieldset']['emailPerso'] && !empty($dossierIntervenant->getEmailPerso())) {
                $params = [
                    'matricule' => $intervenantRh->getCodeRh(),
                    'numero'    => $dossierIntervenant->getEmailPerso(),
                    'dateDebut' => $intervenantRh->getEmailPersoDateDebut(),
                ];

                $this->siham->modifierCoordonneesAgent($params, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO);
            }

            if ($datas['generiqueFieldset']['adressePrincipale']) {

                $numeroVoie = (!empty($dossierIntervenant->getAdresseNumero())) ? $dossierIntervenant->getAdresseNumero() : ' ';
                $natureVoie = (!empty($dossierIntervenant->getAdresseVoirie())) ? $dossierIntervenant->getAdresseVoirie()->getCodeRh() : '';
                $bisTer     = (!empty($dossierIntervenant->getAdresseNumeroCompl())) ? $dossierIntervenant->getAdresseNumeroCompl()->getCodeRh() : '';
                $nomVoie    = (!empty($dossierIntervenant->getAdresseVoie())) ? $dossierIntervenant->getAdresseVoie() : ' ';
                $nomVoie    = Util::stripAccents($nomVoie);
                $nomVoie    = substr($nomVoie, 0, 28);
                $complement = (!empty($dossierIntervenant->getAdresseLieuDit())) ? $dossierIntervenant->getAdresseLieuDit() . ' ' : ' ';
                $complement .= (!empty($dossierIntervenant->getAdressePrecisions())) ? $dossierIntervenant->getAdressePrecisions() : ' ';
                $complement = Util::stripAccents($complement);
                $commune    = Util::stripAccents($dossierIntervenant->getAdresseCommune());
                $codePostal = $dossierIntervenant->getAdresseCodePostal();

                $params = [
                    'matricule'          => $intervenantRh->getCodeRh(),
                    'dateDebut'          => $intervenantRh->getAdresseDateDebut(),
                    'bureauDistributeur' => $commune,
                    'bisTer'             => $bisTer,
                    'noVoie'             => $numeroVoie,
                    'natureVoie'         => $natureVoie,
                    'nomVoie'            => self::cleanDatas(substr($nomVoie, 0, 28)),
                    'complementAdresse'  => self::cleanDatas(substr($complement, 0, 37)),
                    'ville'              => $commune,
                    'codePostal'         => $codePostal,
                    'codePays'           => $dossierIntervenant->getAdressePays()->getCodeIso3(),

                ];

                $this->siham->modifierAdressePrincipaleAgent($params);
            }

            if ($datas['generiqueFieldset']['iban']) {
                $anneeUniversitaire = $intervenant->getAnnee();
                $dateEffet          = $anneeUniversitaire->getDateDebut()->format('Y-m-d');
                $coordonnees        = $this->siham->formatCoordoonneesBancairesForSiham($dossierIntervenant->getIBAN(), $dossierIntervenant->getBIC());

                $params = [
                    'matricule'      => $intervenantRh->getCodeRh(),
                    'dateDebBanque'  => $dateEffet,
                    'paysBanque'     => $coordonnees['paysBanque'],
                    'codeBanque'     => $coordonnees['codeBanque'],
                    'codeAgence'     => $coordonnees['codeAgence'],
                    'numCompte'      => $coordonnees['numCompte'],
                    'cleCompte'      => $coordonnees['cleCompte'],
                    'IBAN'           => $coordonnees['IBAN'],
                    'SWIFT'          => $coordonnees['SWIFT'],
                    'modePaiement'   => '25',
                    'temoinValidite' => '1',
                ];

                $this->siham->modifierCoordonneesBancairesAgent($params);
            }

            return true;
        } catch
        (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function recupererIntervenantRh(Intervenant $intervenant): ?IntervenantRh
    {
        $agent  = null;
        $codeRh = $this->trouverCodeRhByInsee($intervenant);
        if (!empty($codeRh)) {
            $params =
                [
                    'listeMatricules' => [$codeRh],
                ];

            $agent = $this->siham->recupererDonneesPersonnellesAgent($params);
        }
        if (empty($agent)) {
            if (!empty($intervenant->getCodeRh())) {
                $codeRh = $intervenant->getCodeRh();
                $params =
                    [
                        'listeMatricules' => [$codeRh],
                    ];

                $agent = $this->siham->recupererDonneesPersonnellesAgent($params);
            }
        }

        if (!empty($agent)) {
            $intervenantRh = new IntervenantRH();
            $intervenantRh->setNomUsuel($agent->getNomUsuel());
            $intervenantRh->setPrenom($agent->getPrenom());
            $intervenantRh->setTelPerso($agent->getTelephonePerso());
            $intervenantRh->setTelPersoDateDebut($agent->getTelephonePersoDateDebut());
            $intervenantRh->setTelPro($agent->getTelephonePro());
            $intervenantRh->setTelProDateDebut($agent->getTelephoneProDateDebut());
            $intervenantRh->setEmailPro($agent->getEmailPro());
            $intervenantRh->setEmailProDateDebut($agent->getEmailProDateDebut());
            $intervenantRh->setEmailPerso($agent->getEmailPerso());
            $intervenantRh->setEmailPersoDateDebut($agent->getEmailPersoDateDebut());
            $intervenantRh->setIBAN($agent->getIban());
            $intervenantRh->setBIC($agent->getBic());
            $intervenantRh->setCodeRh($agent->getMatricule());
            $intervenantRh->setAdresseNumero($agent->getNoVoieAdresse());
            $bisTer = $this->getServiceAdresseNumeroCompl()->getRepo()->findOneBy(['codeRh' => $agent->getBisTerAdresse()]);
            $intervenantRh->setAdresseNumeroCompl($bisTer);
            $voirie = $this->getServiceVoirie()->getRepo()->findOneBy(['codeRh' => $agent->getNatureVoieAdresse()]);
            $intervenantRh->setAdresseVoirie($voirie);
            $intervenantRh->setAdresseVoie($agent->getNomVoieAdresse());
            $intervenantRh->setAdressePrecisions($agent->getComplementAdresse());
            $intervenantRh->setAdresseCodePostal($agent->getCodePostalAdresse());
            $intervenantRh->setAdresseCommune($agent->getBureauDistributeurAdresse());
            $intervenantRh->setAdresseDateDebut($agent->getDateDebutAdresse());
            $codeSituationMatrimoniale = $agent->getCodeSituationFamVigueur();
            if ($codeSituationMatrimoniale) {
                $situationMatrimoniale = $this->getServiceSituationMatrimoniale()->getSituationMatrimonialeByCode($agent->getCodeSituationFamVigueur());
                $intervenantRh->setSituationMatrimoniale($situationMatrimoniale);
            }


            return $intervenantRh;
        }

        return null;
    }



    public function cloreDossier(Intervenant $intervenant, string $codeStatutSiham): ?bool
    {


        try {
            //On regarde si on est dans le cas d'une cloture pour une mission étudiante pour mettre la bonne date de sortie
            $firstMission = $this->getServiceContrat()->getFirstContratMission($intervenant);
            if (!empty($firstMission)) {
                $dateSortie = $firstMission->getDateFin()->format('Y-m-d');
            } else {
                $anneeUniversitaire = $intervenant->getAnnee();
                $dateSortie         = $anneeUniversitaire->getDateFin()->format('Y-m-d');
            }

            $matricule = '';
            //On récupére le code RH par le INSEE
            $matricule = $this->trouverCodeRhByInsee($intervenant);
            if (!empty($intervenant->getCodeRh()) && empty($matricule)) {
                $matricule = $intervenant->getCodeRh();
            }
            //Valeur par défaut
            $categorieSituation = 'MC140';
            $motifSituation     = 'MC141';
            $config = $this->siham->getConfig();
            $configCloture = ($config['cloture'] !== false) ? $config['cloture'] : null;
            //On regarde si des valeurs ont été spécifié dans la configuration siham
            if ($configCloture !== null) {
                if (array_key_exists($codeStatutSiham, $config['cloture'])) {
                    if (isset($config['cloture'][$codeStatutSiham]['categorie-situation'])) {
                        $categorieSituation = $config['cloture'][$codeStatutSiham]['categorie-situation'];
                    }
                    if (isset($config['cloture'][$codeStatutSiham]['motif-situation'])) {
                        $motifSituation = $config['cloture'][$codeStatutSiham]['motif-situation'];
                    }
                } elseif (isset($config['cloture']['default'])) {
                    if (isset($config['cloture']['default']['categorie-situation'])) {
                        $categorieSituation = $config['cloture']['default']['categorie-situation'];
                    }
                    if (isset($config['cloture']['default']['motif-situation'])) {
                        $motifSituation = $config['cloture']['default']['motif-situation'];
                    }
                } else {
                    if (isset($config['cloture']['categorie-situation'])) {
                        $categorieSituation = $config['cloture']['categorie-situation'];
                    }
                    if (isset($config['cloture']['motif-situation'])) {
                        $motifSituation = $config['cloture']['motif-situation'];
                    }
                }

            }
            $paramsWS = [
                'categorieSituation' => $categorieSituation,
                'dateSortie'         => $dateSortie,
                'matricule'          => $matricule,
                'motifSituation'     => $motifSituation,
                'temoinValidite'     => 1,

            ];


            return $this->siham->cloreDossier($paramsWS);
        } catch (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function recupererListeUO(): ?array
    {
        /*On récupére les UO avec le type paramétré*/
        $uo     = [];
        $typeUO = $this->siham->getConfig()['code-type-structure-affectation'];
        $types  = explode(',', $typeUO);
        //On boucle sur les différents types UO nécessaire au module export siham
        foreach ($types as $code) {
            $params = [
                'codeAdministration' => '',
                'listeUO'            => [[
                    'typeUO' => $code,
                ]],
            ];

            $uo = array_merge($uo, $this->siham->recupererListeUO($params));
        }

        ksort($uo);

        return $uo;
    }



    public function recupererListePositions(): ?array
    {
        return $this->siham->recupererListePositions();
    }



    public function recupererListeEmplois(): ?array
    {
        return $this->siham->recupererListeEmplois();
    }



    public function recupererListeStatuts(): ?array
    {
        return $this->siham->recupererListeStatuts();
    }



    public function recupererListeModalites(): ?array
    {
        return $this->siham->recupererListeModalites();
    }



    public function recupererListeContrats(): ?array
    {
        return $this->siham->recupererListeContrats();
    }



    public function getConnecteurName(): string
    {
        return 'siham';
    }



    public function recupererFieldsetConnecteur(): Fieldset
    {
        $fieldset = new SihamFieldset('connecteurForm', []);

        return $fieldset;
    }

}