<?php

namespace UnicaenSiham\Controller;


use Application\Entity\Db\Intervenant;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenSiham\Exception\SihamException;
use UnicaenSiham\Service\Siham;
use UnicaenSiham\Service\SihamClient;
use UnicaenSiham\Service\Traits\SihamAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController
{

    protected $siham;

    use IntervenantServiceAwareTrait;
    use DossierServiceAwareTrait;

    public function __construct(Siham $siham)
    {
        $this->siham = $siham;
    }



    public function indexAction(): array
    {
        $params = [
            'nomUsuel' => '',
            'prenom'   => '',
        ];

        $agents = [];
        try {

            if ($this->getRequest()->isPost()) {

                $params['nomUsuel'] = $this->getRequest()->getPost('nomUsuel');
                $params['prenom']   = $this->getRequest()->getPost('prenom');
                $agents             = $this->siham->rechercherAgent($params);
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return compact('agents');
    }



    public function voirAction(): array
    {
        $matricule = $this->params()->fromRoute('matricule');
        $agent     = [];
        try {
            if ($this->getRequest()->isPost()) {
                //traitemetn de la modification des données personnelles
                $params   = $this->getRequest()->getPost();
                $paramsWS = [
                    'matricule'         => $params->matricule,
                    'dateDebut'         => $params->dateDebut,
                    'complementAdresse' => $params->complementAdresse,
                    'natureVoie'        => $params->natureVoie,
                    'codePostal'        => $params->codePostal,
                    'ville'             => $params->ville,
                    'nomVoie'           => $params->nomVoieAdresse,
                    '',
                ];
                if (empty($paramsWS['dateDebut'])) {
                    $result = $this->siham->ajouterAdresseAgent($paramsWS);
                } else {
                    $result = $this->siham->modifierAdressePrincipaleAgent($paramsWS);
                }
                //gestion des numéros de téléphone
                //Teléphone pro
                $paramsWS = [
                    'matricule' => $params->matricule,
                    'numero'    => $params->telFixePro,

                ];
                if (!empty($params->telFixePro)) {
                    $result = $this->siham->modifierTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                } else {
                    $result = $this->siham->supprimerTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                }
                $result = $this->siham->modifierTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO);
                //Téléphone perso
                $paramsWS = [
                    'matricule' => $params->matricule,
                    'numero'    => $params->telPortablePerso,

                ];

                if (!empty($params->telPortablePerso)) {
                    $result = $this->siham->modifierTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                } else {
                    $result = $this->siham->supprimerTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                }
                //Mail Pro
                $paramsWS = [
                    'matricule' => $params->matricule,
                    'numero'    => $params->emailPro,

                ];

                if (!empty($params->emailPro)) {
                    $result = $this->siham->modifierEmailAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO);
                } else {
                    // $result = $this->siham->supprimerTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                }

                //Mail Perso
                $paramsWS = [
                    'matricule' => $params->matricule,
                    'numero'    => $params->emailPerso,

                ];

                if (!empty($params->emailPerso)) {
                    $result = $this->siham->modifierEmailAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO);
                } else {
                    // $result = $this->siham->supprimerTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                }

                //Modification des coordonnées bancaires

                /*if (!empty($params->iban)) {
                    $this->siham->ibanToSiham('');
                }*/

                $this->flashMessenger()->addSuccessMessage('Modification effectuée avec succés');
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } finally {
            try {
                $agent = $this->siham->recupererDonneesPersonnellesAgent(['listeMatricules' => [$matricule]]);
            } catch (SihamException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return compact('agent');
    }



    public function saveAdresseAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                //traitemetn de la modification des données personnelles
                $params   = $this->getRequest()->getPost();
                $paramsWS = [
                    'matricule'         => $params->matricule,
                    'dateDebut'         => $params->dateDebut,
                    'complementAdresse' => $params->complementAdresse,
                    'natureVoie'        => $params->natureVoie,
                    'codePostal'        => $params->codePostal,
                    'ville'             => $params->ville,
                    'nomVoie'           => $params->nomVoieAdresse,
                ];

                $result = $this->siham->modifierAdressePrincipaleAgent($paramsWS);

                if ($result) {
                    $this->flashMessenger()->addSuccessMessage('Modification effectuée avec succés');
                }
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $params->matricule]);
    }



    public function saveCoordonneesAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $params = $this->getRequest()->getPost();

                //gestion des numéros de téléphone
                //Teléphone pro
                $paramsWS = [
                    'matricule' => $params->matricule,
                    'numero'    => $params->telFixePro,

                ];
                if (!empty($params->telFixePro)) {
                    $paramsWS = [
                        'matricule' => $params->matricule,
                        'numero'    => $params->telFixePro,
                        'dateDebut' => $params->telFixeProDateDebut,
                    ];
                    $result   = $this->siham->modifierCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO);
                }
                //$result = $this->siham->modifierTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO);
                //Téléphone perso
                if (!empty($params->telPortablePerso)) {
                    $paramsWS = [
                        'matricule' => $params->matricule,
                        'numero'    => $params->telPortablePerso,
                        'dateDebut' => $params->telPortablePersoDateDebut,
                    ];
                    $result   = $this->siham->modifierCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                }

                //Mail Pro
                if (!empty($params->emailPro)) {
                    $paramsWS = [
                        'matricule' => $params->matricule,
                        'numero'    => $params->emailPro,
                        'dateDebut' => $params->emailProDateDebut,
                    ];
                    $result   = $this->siham->modifierCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO);
                }
                //Mail Perso
                if (!empty($params->emailPerso)) {
                    $paramsWS = [
                        'matricule' => $params->matricule,
                        'numero'    => $params->emailPerso,
                        'dateDebut' => $params->emailProDateDebut,
                    ];
                    $result   = $this->siham->modifierCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO);
                }


                $this->flashMessenger()->addSuccessMessage('Coordonnées téléphoniques et Email enregistrées avec succés');
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $params->matricule]);
    }



    public function historiserCoordonneesAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
        $type      = $this->params()->fromRoute('type');


        try {
            $paramsWS = [
                'matricule' => $matricule,
            ];
            switch ($type) {
                case 'telpro':
                    $this->siham->historiserCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO);
                break;

                case 'telperso':
                    $this->siham->historiserCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO);
                break;

                case 'emailperso':
                    $this->siham->historiserCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PERSO);
                break;

                case 'emailpro':
                    $this->siham->historiserCoordonneesAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO);
                break;
            }
            $this->flashMessenger()->addSuccessMessage('Coordonnées historiser avec succès');
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $matricule]);
    }



    public function saveIbanAction()
    {

    }



    public function modifierAdresseAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
        try {
            if ($this->getRequest()->isPost()) {
                //traitemetn de la modification des données personnelles
                $params = $this->getRequest()->getPost();
                $params = [
                    'matricule'         => $params->matricule,
                    'dateDebut'         => $params->dateDebut,
                    'complementAdresse' => $params->complementAdresse,
                    'natureVoie'        => $params->natureVoie,
                    'codePostal'        => $params->codePostal,
                    'ville'             => $params->ville,
                ];

                if (empty($params['dateDebut'])) {
                    $result = $this->siham->ajouterAdresseAgent($params);
                } else {
                    $result = $this->siham->modifierAdresseAgent($params);
                }
                $this->flashMessenger()->addSuccessMessage('Modification effectuée avec succés');
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } finally {
            try {
                $agent = $this->siham->recupererDonneesPersonnellesAgent(['listeMatricules' => [$matricule]]);
            } catch (SihamException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $matricule]);
    }



    public function historiserAdresseAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
        $dateFin   = date("Y-m-d H:i:s");
        $agent     = [];

        try {
            $params = [
                'matricule' => $matricule,
                'dateFin'   => $dateFin,
            ];

            $result = $this->siham->historiserAdresseAgent($params);
            $this->flashMessenger()->addSuccessMessage("Adresse principale historisée avec succés");
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $matricule]);
    }



    public function ajouterAdresseAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
    }



    public function voirNomenclatureAction()
    {
        $nomenclature = $this->params()->fromRoute('nomenclature');
        $result       = $this->siham->recupererNomenclatureRH(['listeNomenclatures' => [$nomenclature]]);

        return compact('result');
    }



    public function renouvellerAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
    }



    public function listeIntervenantsPECAction()
    {
        //On récupérer les intervenants à prendre en charge
        $serviceIntervenant = $this->getServiceIntervenant();
        $sql                = "
            SELECT i.id,i.code, i.nom_usuel,i.prenom,i.code_rh
            FROM intervenant i 
            JOIN intervenant_dossier d ON d.intervenant_id = i.id 
            JOIN contrat c ON c.intervenant_id = i.id AND c.histo_destruction IS NULL AND c.date_retour_signe IS NOT NULL
            WHERE i.annee_id = 2020
            AND code_rh IS NULL
            
        ";

        $intervenants = $serviceIntervenant->getEntityManager()->getConnection()->fetchAll($sql, []);

        return compact('intervenants');
    }



    public function priseEnChargeAgentAction()
    {
        $intervenant        = $this->params()->fromRoute('intervenant');
        $serviceIntervenant = $this->getServiceIntervenant();
        $serviceDossier     = $this->getServiceDossier();

        /*On récupére les UO de type composante*/
        $params = [
            'codeAdministration' => '',
            'listeUO'            => [[
                                         'typeUO' => 'COP',
                                     ]],
        ];

        $uo = $this->siham->recupererListeUO($params);


        $statuts   = $this->siham->recupererListeStatuts();
        $modalites = $this->siham->recupererListeModalites();
        $positions = $this->siham->recupererListePositions();

        /**
         * @var Intervenant $intervenant
         */
        $intervenant        = current($serviceIntervenant->getEntityManager()->getRepository(Intervenant::class)->findBy(['id' => $intervenant]));
        $dossierIntervenant = $serviceDossier->getByIntervenant($intervenant);
        try {
            if ($this->getRequest()->isPost()) {

                /*POSITION ADMINISTRATIVE*/
                $position[] =
                    ['dateEffetPosition' => $this->getRequest()->getPost('anneeUniversitaire'),
                     'position'          => $this->getRequest()->getPost('position-administrative')];

                /*STATUT*/
                $statut[] =
                    ['dateEffetStatut' => $this->getRequest()->getPost('anneeUniversitaire'),
                     'statut'          => 'C0301'];

                /*MODALITE SERVICE*/
                $service[] =
                    ['dateEffetModalite' => $this->getRequest()->getPost('anneeUniversitaire'),
                     'modalite'          => $this->getRequest()->getPost('modaliteService')];

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
                    'debutAdresse'       => $this->getRequest()->getPost('anneeUniversitaire'),
                ];

                /*COORDONNEES BANCAIRES*/
                $coordonnees                   = $this->siham->formatCoordoonneesBancairesForSiham($dossierIntervenant->getIBAN(), $dossierIntervenant->getBIC());
                $coordonnees['dateDebBanque']  = $this->getRequest()->getPost('anneeUniversitaire');
                $coordonnees['temoinValidite'] = '1';
                $coordonnees['modePaiement']   = '25';
                $coordonneesBancaires[]        = $coordonnees;

                /*COORDONNEES TELEPHONIQUES ET MAIL*/
                /*'dateDebutTel' => (isset($numero['dateDebutTel'])) ? strtoupper($numero['dateDebutTel']) : '',
                    'numero'       => (isset($numero['numero'])) ? strtoupper($numero['numero']) : '',
                    'typeNumero'*/
                $coordonneesTelMail[] = '';
                if ($dossierIntervenant->getTelPro()) {
                    $coordonneesTelMail[] = [
                        'dateDebutTel' => $this->getRequest()->getPost('anneeUniversitaire'),
                        'numero'       => $dossierIntervenant->getTelPro(),
                        'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO,
                    ];
                }
                if ($dossierIntervenant->getTelPerso()) {
                    $coordonneesTelMail[] = [
                        'dateDebutTel' => $this->getRequest()->getPost('anneeUniversitaire'),
                        'numero'       => $dossierIntervenant->getTelPerso(),
                        'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_PORTABLE_PERSO,
                    ];
                }
                if ($dossierIntervenant->getEmailPro()) {
                    $coordonneesTelMail[] = [
                        'dateDebutTel' => $this->getRequest()->getPost('anneeUniversitaire'),
                        'numero'       => $dossierIntervenant->getEmailPro(),
                        'typeNumero'   => Siham::SIHAM_CODE_TYPOLOGIE_EMAIL_PRO,
                    ];
                }
                if ($dossierIntervenant->getEmailPerso()) {
                    $coordonneesTelMail[] = [
                        'dateDebutTel' => $this->getRequest()->getPost('anneeUniversitaire'),
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
                    'dateEmbauche'              => $this->getRequest()->getPost('anneeUniversitaire'),
                    'dateNaissance'             => $dossierIntervenant->getDateNaissance()->format('Y-m-d'),
                    'villeNaissance'            => $dossierIntervenant->getCommuneNaissance(),
                    'departementNaissance'      => (!empty($dossierIntervenant->getDepartementNaissance())) ? substr(1, 2, $dossierIntervenant->getDepartementNaissance()->getCode()) : '',
                    'emploi'                    => $this->getRequest()->getPost('emploi'),
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
                    'numeroInsee'               => $dossierIntervenant->getNumeroInsee(),
                    'paysNaissance'             => '',
                    'prenom'                    => $dossierIntervenant->getPrenom(),
                    'sexe'                      => ($dossierIntervenant->getCivilite() == 'M.') ? '1' : '2',
                    'temoinValidite'            => '1',
                    'UO'                        => $this->getRequest()->getPost('uo'),
                ];

                $matricule = $this->siham->priseEnChargeAgent($params);

                $this->flashMessenger()->addSuccessMessage("La prise en charge de l'agent est effective / Code Agent SIHAM : $matricule");
            }
        } catch (SihamException $e) {
            //var_dump($this->siham->getClient()->getLastRequest());
            //die;
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } finally {
            return compact('intervenant', 'dossierIntervenant', 'uo', 'statuts', 'modalites', 'positions');
        }
    }



    public
    function saveAction(): array
    {
        return [];
    }
}
