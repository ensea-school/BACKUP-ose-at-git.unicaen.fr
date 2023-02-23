<?php

namespace ExportRh\Connecteur\Siham;


use Application\Entity\Db\Intervenant;
use Application\Service\Traits\AdresseNumeroComplServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\VoirieServiceAwareTrait;
use ExportRh\Connecteur\ConnecteurRhInterface;
use ExportRh\Entity\IntervenantRh;
use ExportRh\Form\Fieldset\SihamFieldset;
use ExportRh\Service\ExportRhServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenSiham\Exception\SihamException;
use UnicaenSiham\Service\Siham;
use Laminas\Form\Fieldset;


class SihamConnecteur implements ConnecteurRhInterface
{
    use DossierServiceAwareTrait;
    use ExportRhServiceAwareTrait;
    use AdresseNumeroComplServiceAwareTrait;
    use VoirieServiceAwareTrait;

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



    public function recupererIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?IntervenantRh
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


            return $intervenantRh;
        }

        return null;
    }



    public function recupererDonneesAdministrativesIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?array
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



    public function recupererAffectationEnCoursIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?array
    {
        $affectations           = [];
        $donneesAdministratives = $this->recupererDonneesAdministrativesIntervenantRh($intervenant);


        if (!empty($donneesAdministratives['listeAffectations']) || !empty($donneesAdministratives->listeAffectations)) {
            $listeAffectations = (isset($donneesAdministratives['listeAffectations'])) ? $donneesAdministratives['listeAffectations'] : $donneesAdministratives->listeAffectations;

            foreach ($listeAffectations as $affectation) {
                //On prend uniquement les affectations fonctionnelles
                if ($affectation->codeTypeRattachement == 'FUN') {
                    $dateDebutAffectation = new \DateTime($affectation->dateDebutAffectation);
                    $dateFinAffectation   = new \DateTime($affectation->dateFinAffectation);
                    $currentDate          = new \DateTime();
                    if ($currentDate > $dateDebutAffectation and $currentDate < $dateFinAffectation) {
                        $affectations[] = $affectation;
                    }
                }
            };
        }

        return $affectations;
    }



    public function recupererContratEnCoursIntervenantRh(Intervenant $intervenant): ?array
    {
        $contrats               = [];
        $donneesAdministratives = $this->recupererDonneesAdministrativesIntervenantRh($intervenant);


        if (!empty($donneesAdministratives['listeContrats']) || !empty($donneesAdministratives->listeContrats)) {
            $listeContrats = (isset($donneesAdministratives['listeContrats']) && is_array($donneesAdministratives['listeContrats'])) ? $donneesAdministratives['listeContrats'] : [$donneesAdministratives['listeContrats']];


            foreach ($listeContrats as $contrat) {

                $dateDebutContrat = new \DateTime($contrat->dateDebutContrat);
                $dateFinContrat   = new \DateTime($contrat->dateFinReelleContrat);
                $currentDate      = new \DateTime();
                if ($currentDate > $dateDebutContrat and $currentDate < $dateFinContrat) {
                    $contrats[] = $contrat;
                }
            }
        };

        return $contrats;
    }



    public function synchroniserDonneesPersonnellesIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, $datas): bool
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
                $nomVoie    = substr($nomVoie, 0, 32);
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
                    'nomVoie'            => self::cleanDatas(substr($nomVoie, 0, 32)),
                    'complementAdresse'  => self::cleanDatas(substr($complement, 0, 37)),
                    'ville'              => $commune,
                    'codePostal'         => $codePostal,
                    'codePays'           => $dossierIntervenant->getAdressePays()->getCodeIso3(),

                ];


                $this->siham->modifierAdressePrincipaleAgent($params);
            }

            //Fait planter les WS SIHAM....
            /*if ($datas['generiqueFieldset']['iban']) {
                $anneeUniversitaire = $this->getServiceExportRh()->getAnneeUniversitaireEnCours();
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

                $this->siham->modifierCoordonnéesBancairesAgent($params);
            }*/


            return true;
        } catch
        (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function trouverCodeRhByInsee(\Application\Entity\Db\Intervenant $intervenant): ?string
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



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, $datas): ?string
    {
        try {
            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            $anneeUniversitaire = $intervenant->getAnnee();//$this->getServiceExportRh()->getAnneeUniversitaireEnCours();
            $dateEffet          = $anneeUniversitaire->getDateDebut()->format('Y-m-d');
            $dateFin            = $anneeUniversitaire->getDateFin()->format('Y-m-d');

            /*CARRIERE*/
            $carriere = [
                'dateEffetCarriere' => $dateEffet,
                'grade'             => '0000',
                'qualiteStatutaire' => 'N',
                'temoinValidite'    => 1,
            ];
            /*CONTRAT*/
            $contrat[] =
                ['dateDebutContrat'  => $dateEffet,
                 'dateFinContrat'    => $dateFin,
                 'natureContrat'     => 'CO',
                 'typeContrat'       => 'TC01',
                 'typeLienJuridique' => 'TL02',
                ];

            /*POSITION ADMINISTRATIVE*/
            $position[] =
                ['dateEffetPosition' => $dateEffet,
                 'dateFinPrevue'     => $dateFin,
                 'dateFinReelle'     => $dateFin,
                 'position'          => $datas['connecteurForm']['position'],
                 'temoinValidite'    => 1,
                ];

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
            $situationFamiliale[] =
                ['dateEffetSituFam' => $dateEffet,
                 'situFam'          => 'CEL',
                 'temoinValidite'   => 1,
                ];

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
                'nomVoie'            => self::cleanDatas(substr($nomVoie, 0, 32)),
                'numAdresse'         => $numeroVoie,
                'complementAdresse'  => self::cleanDatas(substr($complement, 0, 37)),
                'commune'            => $commune,
                'codePostal'         => $codePostal,
                'codePays'           => $dossierIntervenant->getAdressePays()->getCodeIso3(),
                'debutAdresse'       => $dateEffet,
            ];

            /*COORDONNEES BANCAIRES*/
            $coordonneesBancaires[] = '';
            if ($datas['generiqueFieldset']['iban']) {
                $coordonnees                   = $this->siham->formatCoordoonneesBancairesForSiham($dossierIntervenant->getIBAN(), $dossierIntervenant->getBIC());
                $coordonnees['dateDebBanque']  = $dateEffet;
                $coordonnees['temoinValidite'] = '1';
                $coordonnees['modePaiement']   = '25';

                $coordonneesBancaires[] = $coordonnees;
            }


            $coordonneesTelMail[] = '';
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
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $dateEffet,
                    'numero'       => $dossierIntervenant->getEmailPro(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO,
                ];
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
                //'listeContrats'             => $contrat,
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


            $matricule = $this->siham->priseEnChargeAgent($params);

            return $matricule;
        } catch (SihamException $e) {


            throw new \Exception($e->getMessage());
        }
    }



    public function renouvellerIntervenantRH(\Application\Entity\Db\Intervenant $intervenant, $datas): ?string
    {
        try {
            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);
            $anneeUniversitaire = $intervenant->getAnnee();//$this->getServiceExportRh()->getAnneeUniversitaireEnCours();

            $dateEffet = $anneeUniversitaire->getDateDebut()->format('Y-m-d');
            $dateFin   = $anneeUniversitaire->getDateFin()->format('Y-m-d');

            /*Formatage du matricule*/

            $matricule = '';
            //On récupére le code RH par le INSEE
            $matricule = $this->trouverCodeRhByInsee($intervenant);

            if (!empty($intervenant->getCodeRh()) && empty($matricule)) {
                $matricule = $intervenant->getCodeRh();
            }
            //Si code RH ne contient pas UCN alors on le reformate
            /*if (!strstr($matricule, 'UCN')) {
                $matricule = $this->siham->getCodeAdministration() . str_pad($matricule, 9, '0', STR_PAD_LEFT);
            }/*/


            /*POSITION ADMINISTRATIVE*/
            $position[] =
                ['dateEffetPosition' => $dateEffet,
                 'dateFinPrevue'     => $dateFin,
                 'dateFinReelle'     => $dateFin,
                 'position'          => $datas['connecteurForm']['position'],
                 'temoinValidite'    => 1,
                ];


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

            /*CONTRAT*/
            $contrat[] =
                ['dateDebutContrat'  => $dateEffet,
                 'dateFinContrat'    => $dateFin,
                 'natureContrat'     => 'CO',
                 'typeContrat'       => 'TC01',
                 'typeLienJuridique' => 'TL01',
                ];


            $params = [
                'categorieEntree'        => 'ACTIVE',
                'dateRenouvellement'     => $dateEffet,
                'emploi'                 => $datas['connecteurForm']['emploi'],
                'listeCarriere'          => $carriere,
                'listeModalitesServices' => $service,
                'listeStatuts'           => $statut,
                //'listeContrats'          => $contrat,
                'listePositions'         => $position,
                'motifEntree'            => 'REN',
                'matricule'              => $matricule,
                'temoinValidite'         => 1,
                'UO'                     => $datas['connecteurForm']['affectation'],
            ];


            $matricule = $this->siham->renouvellementAgent($params);

            //Mise à jour des données personnelles de l'agent
            $this->synchroniserDonneesPersonnellesIntervenantRh($intervenant, $datas);

            return $matricule;
        } catch (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function cloreDossier(Intervenant $intervenant): ?bool
    {

        try {
            $anneeUniversitaire = $this->getServiceExportRh()->getAnneeUniversitaireEnCours();
            $dateSortie         = $anneeUniversitaire->getDateFin()->format('Y-m-d');

            $matricule = '';
            //On récupére le code RH par le INSEE
            $matricule = $this->trouverCodeRhByInsee($intervenant);
            if (!empty($intervenant->getCodeRh()) && empty($matricule)) {
                $matricule = $intervenant->getCodeRh();
            }

            $paramsWS = [
                'categorieSituation' => 'MC140',
                'dateSortie'         => $dateSortie,
                'matricule'          => $matricule,
                'motifSituation'     => 'MC141',

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



    public static function cleanDatas($str, $strict = false, $encoding = 'UTF-8')
    {
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

}