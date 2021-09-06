<?php

namespace ExportRh\Connecteur\Siham;


use Application\Entity\Db\Intervenant;
use Application\Service\Traits\DossierServiceAwareTrait;
use ExportRh\Connecteur\ConnecteurRhInterface;
use ExportRh\Entity\IntervenantRh;
use ExportRh\Form\Fieldset\SihamFieldset;
use UnicaenSiham\Exception\SihamException;
use UnicaenSiham\Service\Siham;
use Zend\Form\Fieldset;
use Zend\Validator\Date;


class SihamConnecteur implements ConnecteurRhInterface
{
    use DossierServiceAwareTrait;

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

        if (!empty($intervenant->getCodeRh())) {
            $codeRh = $intervenant->getCodeRh();
            //Si code RH ne contient pas UCN alors on le reformate par rapport au masque matricule de SIHAM
            if (!strstr($codeRh, 'UCN')) {
                $codeRh = $this->siham->getCodeAdministration() . str_pad($codeRh, 9, '0', STR_PAD_LEFT);
            }

            $params =
                [
                    'listeMatricules' => [$codeRh],
                ];

            $agent = $this->siham->recupererDonneesPersonnellesAgent($params);

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
                $intervenantRh->setAdresseNumeroCompl(null);
                $intervenantRh->setAdresseVoirie(null);
                $intervenantRh->setAdresseVoie($agent->getNomVoieAdresse());
                $intervenantRh->setAdressePrecisions($agent->getComplementAdresse());
                $intervenantRh->setAdresseCodePostal($agent->getCodePostalAdresse());
                $intervenantRh->setAdresseCommune($agent->getBureauDistributeurAdresse());
                $intervenantRh->setAdresseDateDebut($agent->getDateDebutAdresse());


                return $intervenantRh;
            }
        }

        return null;
    }



    public function recupererDonneesAdministrativesIntervenantRh(\Application\Entity\Db\Intervenant $intervenant): ?array
    {
        try {
            if (!empty($intervenant->getCodeRh())) {
                $codeRh = $intervenant->getCodeRh();
                //Si code RH ne contient pas UCN alors on le reformate
                if (!strstr($codeRh, 'UCN')) {
                    $codeRh = $this->siham->getCodeAdministration() . str_pad($codeRh, 9, '0', STR_PAD_LEFT);
                }


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
                    $affectations[] = $affectation;
                }
            };
        }

        return $affectations;
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

            //TODO : Synchroniser l'adresse
            if ($datas['generiqueFieldset']['adressePrincipale']) {

                $adresse = '';
                $adresse .= (!empty($dossierIntervenant->getAdresseNumero())) ? $dossierIntervenant->getAdresseNumero() . ' ' : '';
                $adresse .= (!empty($dossierIntervenant->getAdresseNumeroCompl())) ? $dossierIntervenant->getAdresseNumeroCompl() . ' ' : '';
                $adresse .= (!empty($dossierIntervenant->getAdresseVoirie())) ? $dossierIntervenant->getAdresseVoirie() . ' ' : '';
                $adresse .= (!empty($dossierIntervenant->getAdresseVoie())) ? $dossierIntervenant->getAdresseVoie() . ' ' : '';
                $adresse .= (!empty($dossierIntervenant->getAdressePrecisions())) ? $dossierIntervenant->getAdressePrecisions() . ' ' : '';


                $params = [
                    'matricule'          => $intervenantRh->getCodeRh(),
                    'dateDebut'          => $intervenantRh->getAdresseDateDebut(),
                    'bureauDistributeur' => $dossierIntervenant->getAdresseCommune(),
                    'complementAdresse'  => $adresse,
                    'noVoie'             => ' ',
                    'natureVoie'         => '',
                    'nomVoie'            => ' ',
                    'commune'            => $dossierIntervenant->getAdresseCommune(),
                    'codePostal'         => $dossierIntervenant->getAdresseCodePostal(),
                    'codePays'           => $dossierIntervenant->getAdressePays()->getCode(),

                ];

                $this->siham->modifierAdressePrincipaleAgent($params);
            }


            return true;
        } catch
        (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function prendreEnChargeIntervenantRh(\Application\Entity\Db\Intervenant $intervenant, $datas): ?string
    {
        try {
            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);

            /* Récupération du dossier de l'intervenant */
            $dossierIntervenant = $this->getServiceDossier()->getByIntervenant($intervenant);


            /*POSITION ADMINISTRATIVE*/
            $position[] =
                ['dateEffetPosition' => $datas['connecteurForm']['anneeUniversitaire'],
                 'position'          => $datas['connecteurForm']['position']];

            /*STATUT*/
            $statut[] =
                ['dateEffetStatut' => $datas['connecteurForm']['anneeUniversitaire'],
                 'statut'          => $datas['connecteurForm']['statut']];

            /*MODALITE SERVICE*/
            $service[] =
                ['dateEffetModalite' => $datas['connecteurForm']['anneeUniversitaire'],
                 'modalite'          => $datas['connecteurForm']['modaliteService']];

            /*COORDONNEES POSTALES*/
            $adresse = '';
            $adresse .= (!empty($dossierIntervenant->getAdresseNumero())) ? $dossierIntervenant->getAdresseNumero() . ' ' : '';
            $adresse .= (!empty($dossierIntervenant->getAdresseNumeroCompl())) ? $dossierIntervenant->getAdresseNumeroCompl() . ' ' : '';
            $adresse .= (!empty($dossierIntervenant->getAdresseVoirie())) ? $dossierIntervenant->getAdresseVoirie() . ' ' : '';
            $adresse .= (!empty($dossierIntervenant->getAdresseVoie())) ? $dossierIntervenant->getAdresseVoie() . ' ' : '';
            $adresse .= (!empty($dossierIntervenant->getAdressePrecisions())) ? $dossierIntervenant->getAdressePrecisions() . ' ' : '';

            $coordonneesPostales[] = [
                'bureauDistributeur' => $dossierIntervenant->getAdresseCommune(),
                'complementAdresse'  => $adresse,
                'commune'            => $dossierIntervenant->getAdresseCommune(),
                'codePostal'         => $dossierIntervenant->getAdresseCodePostal(),
                'codePays'           => $dossierIntervenant->getAdressePays()->getCode(),
                'debutAdresse'       => $datas['connecteurForm']['anneeUniversitaire'],
            ];

            //TODO : travailler les coordonnées bancaires pour la prise en charge
            /*COORDONNEES BANCAIRES*/
            $coordonnees                   = $this->siham->formatCoordoonneesBancairesForSiham($dossierIntervenant->getIBAN(), $dossierIntervenant->getBIC());
            $coordonnees['dateDebBanque']  = $datas['connecteurForm']['anneeUniversitaire'];
            $coordonnees['temoinValidite'] = '1';
            $coordonnees['modePaiement']   = '25';
            $coordonneesBancaires[]        = $coordonnees;


            $coordonneesTelMail[] = '';
            if ($dossierIntervenant->getTelPro()) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $datas['connecteurForm']['anneeUniversitaire'],
                    'numero'       => $dossierIntervenant->getTelPro(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO,
                ];
            }
            if ($dossierIntervenant->getTelPerso()) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $datas['connecteurForm']['anneeUniversitaire'],
                    'numero'       => $dossierIntervenant->getTelPerso(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO,
                ];
            }
            if ($dossierIntervenant->getEmailPro()) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $datas['connecteurForm']['anneeUniversitaire'],
                    'numero'       => $dossierIntervenant->getEmailPro(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO,
                ];
            }
            if ($dossierIntervenant->getEmailPerso()) {
                $coordonneesTelMail[] = [
                    'dateDebutTel' => $datas['connecteurForm']['anneeUniversitaire'],
                    'numero'       => $dossierIntervenant->getEmailPerso(),
                    'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO,
                ];
            }

            /*NATIONALITES*/
            $nationalites[] = [
                'nationalite'   => 'FRA',//$dossierIntervenant->getPaysNationalite()->getCode(),
                'temPrincipale' => 1,
            ];

            $params = [
                'categorieEntree'           => 'ACTIVE',
                'civilite'                  => ($dossierIntervenant->getCivilite() == 'M.') ? '1' : '2',
                'dateEmbauche'              => $datas['connecteurForm']['anneeUniversitaire'],
                'dateNaissance'             => $dossierIntervenant->getDateNaissance()->format('Y-m-d'),
                'villeNaissance'            => $dossierIntervenant->getCommuneNaissance(),
                'departementNaissance'      => (!empty($dossierIntervenant->getDepartementNaissance())) ? substr(1, 2, $dossierIntervenant->getDepartementNaissance()->getCode()) : '',
                'paysNaissance'             => 'FRA',
                'emploi'                    => $datas['connecteurForm']['emploi'],
                'listeCoordonneesPostales'  => $coordonneesPostales,
                'listeCoordonneesBancaires' => $coordonneesBancaires,
                'listeModalitesServices'    => $service,
                'listeStatuts'              => $statut,
                'listeNationalites'         => $nationalites,
                'listeNumerosTelephoneFax'  => $coordonneesTelMail,
                'listePositions'            => $position,
                'motifEntree'               => 'PEC',
                'nomPatronymique'           => $dossierIntervenant->getNomPatronymique(),
                'nomUsuel'                  => $dossierIntervenant->getNomUsuel(),
                'numeroInsee'               => (!$dossierIntervenant->getNumeroInseeProvisoire()) ? $dossierIntervenant->getNumeroInsee() : '',
                'numeroInseeProvisoire'     => ($dossierIntervenant->getNumeroInseeProvisoire()) ? $dossierIntervenant->getNumeroInsee() : '',
                'prenom'                    => $dossierIntervenant->getPrenom(),
                'sexe'                      => ($dossierIntervenant->getCivilite() == 'M.') ? '1' : '2',
                'temoinValidite'            => '1',
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
            $currentDate        = new \DateTime();
            $currentMonth       = $currentDate->format('m');
            if ($currentMonth < 9) {
                $dateEffet = $datas['connecteurForm']['anneeUniversitaire'] + 1 . $currentDate->format('-m-d');
            } else {
                $dateEffet = $datas['connecteurForm']['anneeUniversitaire'] . $currentDate->format('-m-d');
            }


            /*Formatage du matricule*/
            $matricule = $intervenant->getCodeRh();
            //Si code RH ne contient pas UCN alors on le reformate
            if (!strstr($matricule, 'UCN')) {
                $matricule = $this->siham->getCodeAdministration() . str_pad($matricule, 9, '0', STR_PAD_LEFT);
            }


            /*POSITION ADMINISTRATIVE*/
            $position[] =
                ['dateEffetPosition' => $dateEffet,
                 'position'          => $datas['connecteurForm']['position']];

            /*STATUT*/
            $statut[] =
                ['dateEffetStatut' => $dateEffet,
                 'statut'          => $datas['connecteurForm']['statut']];

            /*MODALITE SERVICE*/
            $service[] =
                ['dateEffetModalite' => $dateEffet,
                 'modalite'          => $datas['connecteurForm']['modaliteService']];


            $params = [
                'categorieEntree'        => 'ACTIVE',
                'dateRenouvellement'     => $dateEffet,
                'emploi'                 => $datas['connecteurForm']['emploi'],
                'listeModalitesServices' => $service,
                'listeStatuts'           => $statut,

                'listePositions' => $position,
                'motifEntree'    => 'REN',
                'matricule'      => $matricule,
                'temoinValidite' => '1',
                'UO'             => $datas['connecteurForm']['affectation'],
            ];

            $matricule = $this->siham->renouvellementAgent($params);


            return $matricule;
        } catch (SihamException $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function recupererListeUO(): ?array
    {
        /*On récupére les UO de type composante*/
        $params = [
            'codeAdministration' => '',
            'listeUO'            => [[
                                         'typeUO' => 'COP',
                                     ]],
        ];

        $uo = $this->siham->recupererListeUO($params);

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