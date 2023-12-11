<?php

namespace Paiement;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Paiement\Assertion\PaiementAssertion;
use Paiement\Controller\PaiementController;
use Paiement\Tbl\Process\PaiementProcess;
use Paiement\Tbl\Process\PaiementProcessFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'paiement' => [
            'route'         => '/paiement',
            'controller'    => Controller\PaiementController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
            'may_terminate' => true,
            'child_routes'  => [
                'etat-demande-paiement'                => [
                    'route'      => '/etat-demande-paiement',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'etatPaiement',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::A_METTRE_EN_PAIEMENT,
                    ],
                ],
                'mise-en-paiement'                     => [
                    'route'       => '/mise-en-paiement/:structure/:intervenants',
                    'controller'  => Controller\PaiementController::class,
                    'action'      => 'MiseEnPaiement',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                ],
                'details-calculs'      => [
                    'route'       => '/details-calculs/:intervenant/',
                    'controller'  => Controller\PaiementController::class,
                    'action'      => 'DetailsCalculs',
                    'privileges'  => Privileges::MISE_EN_PAIEMENT_DETAILS,
                ],
                'etat-paiement'                        => [
                    'route'      => '/etat-paiement',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'etatPaiement',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                    ],
                ],
                'mises-en-paiement-csv'                => [
                    'route'      => '/mises-en-paiement-csv',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'misesEnPaiementCsv',
                ],
                'extraction-paie'                      => [
                    'route'      => '/extraction-paie[/:type][/:periode]',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'extractionPaie',
                ],
                'extraction-paie-prime'                => [
                    'route'      => '/extraction-paie-prime[/:periode]',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'extractionPaiePrime',
                ],
                'imputation-siham'                     => [
                    'route'      => '/imputation-siham',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'imputationSiham',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                    ],
                ],
                'import-numero-pec'                    => [
                    'route'      => '/import-numero-pec',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'importNumeroPec',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE,
                ],
                'demande-mise-en-paiement-lot'         => [
                    'route'       => '/demande-mise-en-paiement-lot[/:structure]',
                    'controller'  => Controller\PaiementController::class,
                    'action'      => 'demandeMiseEnPaiementLot',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => Privileges::MISE_EN_PAIEMENT_DEMANDE,

                ],
                'process-demande-mise-en-paiement-lot' => [
                    'route'      => '/process-demande-mise-en-paiement-lot',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'processDemandeMiseEnPaiementLot',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    'assertion'  => PaiementAssertion::class,
                ],
                'pilotage'                             => [
                    'route'      => '/pilotage',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'pilotage',
                    'privileges' => Privileges::PILOTAGE_VISUALISATION,
                ],
                'ecarts-etats'                         => [
                    'route'      => '/ecarts-etats',
                    'action'     => 'ecartsEtats',
                    'controller' => Controller\PaiementController::class,
                    'privileges' => Privileges::PILOTAGE_ECARTS_ETATS,
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                'demande-mise-en-paiement'       => [
                    'label'               => "Demande de mise en paiement",
                    'title'               => "Demande de mise en paiement",
                    'route'               => 'intervenant/mise-en-paiement/demande',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'withtarget'          => true,
                    'workflow-etape-code' => WfEtape::CODE_DEMANDE_MEP,
                    'resource'            => PrivilegeController::getResourceId(Controller\PaiementController::class, 'demandeMiseEnPaiement'),
                    'visible'             => Assertion\PaiementAssertion::class,
                    'order'               => 16,
                ],
                'visualisation-mise-en-paiement' => [
                    'label'               => "Visualisation des mises en paiement",
                    'title'               => "Visualisation des mises en paiement",
                    'route'               => 'intervenant/mise-en-paiement/visualisation',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'withtarget'          => true,
                    'workflow-etape-code' => WfEtape::CODE_SAISIE_MEP,
                    'resource'            => PrivilegeController::getResourceId(Controller\PaiementController::class, 'visualisationMiseEnPaiement'),
                    'visible'             => Assertion\PaiementAssertion::class,
                    'order'               => 17,
                ],
                'edition-mise-en-paiement'       => [
                    'label'        => "Annulation de mises en paiement",
                    'title'        => "Annulation de mises en paiement",
                    'route'        => 'intervenant/mise-en-paiement/edition',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => PrivilegeController::getResourceId(Controller\PaiementController::class, 'editionMiseEnPaiement'),
                    'visible'      => Assertion\PaiementAssertion::class,
                    'order'        => 18,
                ],
                'detail-calculs-paiements' => [
                    'label'        => "Détails de calculs des paiements",
                    'title'        => "Détails de calculs des paiements",
                    'route'        => 'paiement/details-calculs',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => PrivilegeController::getResourceId(Controller\PaiementController::class, 'detailsCalculs'),
                    'visible'      => Assertion\PaiementAssertion::class,
                    'order'        => 19,
                ],
            ],
        ],
        'gestion'     => [
            'pages' => [
                'paiement' => [
                    'label'    => "Paiement",
                    'title'    => "Paiement",
                    'route'    => 'paiement',
                    'icon'     => 'fas fa-credit-card',
                    'color'    => '#F5E79E',
                    'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                    'order'    => 40,
                    'pages'    => [
                        'etat-demande-paiement' => [
                            'label'    => "Mises en paiement",
                            'title'    => "Mises en paiement",
                            'route'    => 'paiement/etat-demande-paiement',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                        ],
                        'etat-paiement'         => [
                            'label'    => "État de paiement",
                            'title'    => "État de paiement",
                            'route'    => 'paiement/etat-paiement',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                        ],
                        'mises-en-paiement-csv' => [
                            'label'    => "Mises en paiement (CSV)",
                            'title'    => "Extraction des mises en paiement et demandes de mises en paiement au format tableur (CSV)",
                            'route'    => 'paiement/mises-en-paiement-csv',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_CSV),
                        ],
                        'extraction-paie'       => [
                            'label'    => "Extraction paie",
                            'title'    => "Export des données de paiement au format attendu",
                            'route'    => 'paiement/extraction-paie',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE),
                        ],
                        'extraction-paie-prime' => [
                            'label'    => "Extraction des indemnités de fin de contrat",
                            'title'    => "Export des données pour payer les indemnités de fin de contrat des étudiantes",
                            'route'    => 'paiement/extraction-paie-prime',
                            'resource' => Privileges::getResourceId(Privileges::MISSION_PRIME_GESTION),
                        ],

                        'imputation-siham'             => [
                            'label'    => "Imputation budgétaire SIHAM",
                            'title'    => "Export des données pour chargement en masse des imputations budgétaires dans SIHAM",
                            'route'    => 'paiement/imputation-siham',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE),
                        ],
                        'import-numero-pec'            => [
                            'label'    => "Import des numéros de prise en charge",
                            'title'    => "Importer les numéros de prise en charge pour automatiser le fichier de paie",
                            'route'    => 'paiement/import-numero-pec',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE),
                        ],
                        'demande-mise-en-paiement-lot' => [
                            'label' => "Demande de mise en paiement par lot",
                            'title' => "Permet de demander l'ensemble des mises en paiement pour une structure",
                            'route' => 'paiement/demande-mise-en-paiement-lot',

                        ],
                    ],
                ],
                'pilotage' => [
                    'label'    => 'Pilotage',
                    'title'    => 'Pilotage',
                    'icon'     => 'fas fa-chart-line',
                    'route'    => 'paiement/pilotage',
                    'resource' => PrivilegeController::getResourceId(PaiementController::class, 'pilotage'),
                    'pages'    => [
                        'ecarts-etats' => [
                            'label'       => 'Ecarts d\'heures complémentaires (CSV)',
                            'title'       => 'Ecarts d\'heures complémentaires (CSV)',
                            'description' => 'Export CSV des HETD (ne porte que sur les heures complémentaires et non sur le service dû)',
                            'route'       => 'paiement/ecarts-etats',
                            'resource'    => PrivilegeController::getResourceId(PaiementController::class, 'ecartsEtats'),
                        ],
                    ],
                    'order'    => 20,
                    'color'    => '#00A020',
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['demandeMiseEnPaiement', 'demandeMiseEnPaiementLot', 'processDemandeMiseEnPaiementLot'],
            'privileges' => [
                Privileges::MISE_EN_PAIEMENT_DEMANDE,
            ],
            'assertion'  => \Paiement\Assertion\PaiementAssertion::class,
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['visualisationMiseEnPaiement'],
            'privileges' => [
                Privileges::MISE_EN_PAIEMENT_DEMANDE,
                Privileges::MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT,
            ],
            'assertion'  => \Paiement\Assertion\PaiementAssertion::class,
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['editionMiseEnPaiement'],
            'privileges' => [
                Privileges::MISE_EN_PAIEMENT_EDITION,
            ],
            'assertion'  => \Paiement\Assertion\PaiementAssertion::class,
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['etatPaiement'],
            'privileges' => [
                Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
            ],
            'assertion'  => \Paiement\Assertion\PaiementAssertion::class,
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['miseEnPaiement'],
            'privileges' => [Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION],
            'assertion'  => \Paiement\Assertion\PaiementAssertion::class,
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['misesEnPaiementCsv'],
            'privileges' => [
                Privileges::MISE_EN_PAIEMENT_EXPORT_CSV,
            ],
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['extractionPaie', 'imputationSiham', 'importNumeroPec'],
            'privileges' => [Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE],
        ],
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['extractionPaiePrime'],
            'privileges' => [Privileges::MISSION_PRIME_GESTION],
        ],
    ],

    'rules' => [
        [
            'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
            'resources'  => 'MiseEnPaiement',
            'assertion'  => \Paiement\Assertion\PaiementAssertion::class,
        ],
    ],

    'services' => [
        Service\ServiceAPayerService::class                      => Service\ServiceAPayerServiceFactory::class,
        Service\MiseEnPaiementService::class                     => Service\MiseEnPaiementServiceFactory::class,
        Service\MiseEnPaiementIntervenantStructureService::class => Service\MiseEnPaiementIntervenantStructureServiceFactory::class,
        Service\TblPaiementService::class                        => Service\TblPaiementServiceFactory::class,
        Service\MotifNonPaiementService::class                   => Service\MotifNonPaiementServiceFactory::class,
        Service\NumeroPriseEnChargeService::class                => Service\NumeroPriseEnChargeServiceFactory::class,
        Assertion\PaiementAssertion::class                       => \UnicaenPrivilege\Assertion\AssertionFactory::class,
        PaiementProcess::class                                   => PaiementProcessFactory::class,
    ],

    'view_helpers' => [
        'demandeMiseEnPaiement' => View\Helper\DemandeMiseEnPaiementViewHelperFactory::class,
        'typeHeures'            => View\Helper\TypeHeuresViewHelperFactory::class,
    ],

    'forms' => [
        Form\Paiement\MiseEnPaiementForm::class          => Form\Paiement\MiseEnPaiementFormFactory::class,
        Form\Paiement\MiseEnPaiementRechercheForm::class => Form\Paiement\MiseEnPaiementRechercheFormFactory::class,
    ],

    'controllers' => [
        Controller\PaiementController::class => Controller\PaiementControllerFactory::class,
    ],
];