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

        /*UO*/
        $params = [
            'codeAdministration' => '',
            'listeUO'            => [[
                                         'typeUO' => 'COP',
                                     ]],
        ];

        $uo = $this->siham->recupererListeUO($params);

        /*Statut*/
        $params  = [
            'codeAdministration' => 'UCN',
            'dateObservation'    => date('Y-m-d'),
            'listeNomenclatures' => ['HJ8'],
        ];
        $statuts = $this->siham->recupererNomenclatureRH($params);

        /*modalite*/
        $params    = [
            'codeAdministration' => 'UCN',
            'dateObservation'    => date('Y-m-d'),
            'listeNomenclatures' => ['UHU'],
        ];
        $modalites = $this->siham->recupererNomenclatureRH($params);

        /*position*/
        $params    = [
            'codeAdministration' => 'UCN',
            'dateObservation'    => date('Y-m-d'),
            'listeNomenclatures' => ['HKK'],
        ];
        $positions = $this->siham->recupererNomenclatureRH($params);


        /**
         * @var Intervenant $intervenant
         */
        $intervenant        = current($serviceIntervenant->getEntityManager()->getRepository(Intervenant::class)->findBy(['id' => $intervenant]));
        $dossierIntervenant = $serviceDossier->getByIntervenant($intervenant);
        try {
            if ($this->getRequest()->isPost()) {

                /*POSITION ADMINISTRATIVE ===> position*/
                $position[] =
                    ['dateEffetModalite' => $this->getRequest()->getPost('dateEmbauche'),
                     'position'          => $this->getRequest()->getPost('position-administrative')];

                /*STATUT*/
                $statut[] =
                    ['dateEffetStatut' => $this->getRequest()->getPost('dateEmbauche'),
                     'statut'          => 'C0301'];

                /*MODALITE SERVICE ===> Mouvement*/
                $service[] =
                    ['dateEffetModalite' => $this->getRequest()->getPost('dateEmbauche'),
                     'modalite'          => $this->getRequest()->getPost('modaliteService')];


                $params = [
                    'categorieEntree'           => 'ACTIVE',
                    'civilite'                  => '1',
                    'codeAdministration'        => 'UCN',
                    'codeEtablissement'         => '0141408E',
                    'dateEmbauche'              => $this->getRequest()->getPost('dateEmbauche'),
                    'dateNaissance'             => $dossierIntervenant->getDateNaissance()->format('Y-m-d'),
                    'villeNaissance'            => $dossierIntervenant->getCommuneNaissance(),
                    'departementNaissance'      => '',
                    'emploi'                    => $this->getRequest()->getPost('emploi'),
                    'listeCoordonneesPostales'  => '',
                    'listeCoordonneesBancaires' => '',
                    'listeModalitesServices'    => $service,
                    'listeStatuts'              => $statut,
                    'listeNationalites'         => '',
                    'listeNumerosTelephoneFax'  => '',
                    'listePositions'            => $position,
                    'motifEntree'               => 'PEC',
                    'nomPatronymique'           => '',
                    'nomUsuel'                  => $dossierIntervenant->getNomUsuel(),
                    'numeroInsee'               => '',
                    'paysNaissance'             => '',
                    'prenom'                    => $dossierIntervenant->getPrenom(),
                    'sexe'                      => ($dossierIntervenant->getCivilite() == 'M . ') ? '1' : '2',
                    'temoinValidite'            => '1',
                    'UO'                        => $this->getRequest()->getPost('uo'),
                ];

                $matricule = $this->siham->priseEnChargeAgent($params);

                $this->flashMessenger()->addSuccessMessage("La prise en charge de l'agent est effective / Code Agent SIHAM : $matricule");
            }
        } catch (SihamException $e) {
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
