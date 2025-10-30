<?php

namespace Paiement;

use Application\Provider\Privileges;
use Paiement\Tbl\Process\PaiementProcess;
use Paiement\Tbl\Process\PaiementProcessFactory;

return [
    'routes' => [
        'paiement'    => [
            'route'         => '/paiement',
            'controller'    => Controller\PaiementController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
            'may_terminate' => true,
            'child_routes'  => [
                'etat-demande-paiement' => [
                    'route'      => '/etat-demande-paiement',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'etatPaiement',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::A_METTRE_EN_PAIEMENT,
                    ],
                ],
                'mise-en-paiement'      => [
                    'route'       => '/mise-en-paiement/:structure/:intervenants',
                    'controller'  => Controller\PaiementController::class,
                    'action'      => 'miseEnPaiement',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => [Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION],
                    'assertion'   => Assertion\PaiementAssertion::class,
                ],
                'details-calculs'       => [
                    'route'      => '/details-calculs/:intervenant/',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'detailsCalculs',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_DETAILS,
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                'etat-paiement'         => [
                    'route'      => '/etat-paiement',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'etatPaiement',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                    ],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                    ],
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                'mises-en-paiement-csv' => [
                    'route'      => '/mises-en-paiement-csv',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'misesEnPaiementCsv',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_EXPORT_CSV,
                ],
                'extraction-paie'       => [
                    'route'      => '/extraction-paie[/:type][/:periode]',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'extractionPaie',
                    'privileges' => [Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE],
                ],
                'extraction-paie-prime' => [
                    'route'      => '/extraction-paie-prime[/:periode]',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'extractionPaiePrime',
                    'privileges' => [Privileges::MISSION_PRIME_GESTION],
                ],
                'imputation-siham'      => [
                    'route'      => '/imputation-siham',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'imputationSiham',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                    ],
                    'privileges' => [Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE],
                ],
                'import-numero-pec'     => [
                    'route'      => '/import-numero-pec',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'importNumeroPec',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE,
                ],
                'pilotage'              => [
                    'route'      => '/pilotage',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'pilotage',
                    'privileges' => [Privileges::PILOTAGE_ECARTS_ETATS,Privileges::MODIF_SERVICE_DU_EXPORT_CSV],
                ],
                'ecarts-etats'          => [
                    'route'      => '/ecarts-etats',
                    'action'     => 'ecartsEtats',
                    'controller' => Controller\PaiementController::class,
                    'privileges' => Privileges::PILOTAGE_ECARTS_ETATS,
                ],
            ],
        ],
        'intervenant' => [
            'child_routes' => [
                'mise-en-paiement' => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/mise-en-paiement',
                    'controller'    => Controller\PaiementController::class,
                    'child_routes'  => [
                        'visualisation' => [
                            'route'      => '/visualisation',
                            'controller' => Controller\PaiementController::class,
                            'action'     => 'visualisationMiseEnPaiement',
                            'privileges' => [
                                Privileges::MISE_EN_PAIEMENT_DEMANDE,
                                Privileges::MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT,
                            ],
                            'assertion'  => Assertion\PaiementAssertion::class,
                        ],
                        'edition'       => [
                            'route'      => '/edition',
                            'controller' => Controller\PaiementController::class,
                            'action'     => 'editionMiseEnPaiement',
                            'privileges' => Privileges::MISE_EN_PAIEMENT_EDITION,
                            'assertion'  => Assertion\PaiementAssertion::class,
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant'       => [
            'pages' => [
                'visualisation-mise-en-paiement' => [
                    'label' => "Visualisation des mises en paiement",
                    'title' => "Visualisation des mises en paiement",
                    'route' => 'intervenant/mise-en-paiement/visualisation',
                    'order' => 17,
                ],
            ],
        ],
        'intervenant-admin' => [
            'pages' => [
                'edition-mise-en-paiement' => [
                    'label' => "Annulation de mises en paiement",
                    'icon'  => 'fas fa-coins',
                    'title' => "Annulation de mises en paiement",
                    'route' => 'intervenant/mise-en-paiement/edition',
                    'order' => 18,
                ],
                'detail-calculs-paiements' => [
                    'label' => "Détails de calculs des paiements",
                    'icon'  => 'fas fa-magnifying-glass',
                    'title' => "Détails de calculs des paiements",
                    'route' => 'paiement/details-calculs',
                    'order' => 19,
                ],
            ],
        ],
        'gestion'           => [
            'pages' => [
                'paiement' => [
                    'label' => "Paiement",
                    'title' => "Paiement",
                    'route' => 'paiement',
                    'icon'  => 'fas fa-credit-card',
                    'color' => '#F5E79E',
                    'order' => 40,
                    'pages' => [
                        'etat-demande-paiement' => [
                            'label' => "Mises en paiement",
                            'title' => "Mises en paiement",
                            'route' => 'paiement/etat-demande-paiement',
                        ],
                        'etat-paiement'         => [
                            'label' => "État de paiement",
                            'title' => "État de paiement",
                            'route' => 'paiement/etat-paiement',
                        ],
                        'mises-en-paiement-csv' => [
                            'label' => "Mises en paiement (CSV)",
                            'title' => "Extraction des mises en paiement et demandes de mises en paiement au format tableur (CSV)",
                            'route' => 'paiement/mises-en-paiement-csv',
                        ],
                        'extraction-paie'       => [
                            'label' => "Extraction paie",
                            'title' => "Export des données de paiement au format attendu",
                            'route' => 'paiement/extraction-paie',
                        ],
                        'extraction-paie-prime' => [
                            'label' => "Extraction des indemnités de fin de contrat",
                            'title' => "Export des données pour payer les indemnités de fin de contrat des étudiantes",
                            'route' => 'paiement/extraction-paie-prime',
                        ],

                        'imputation-siham'  => [
                            'label' => "Imputation budgétaire SIHAM",
                            'title' => "Export des données pour chargement en masse des imputations budgétaires dans SIHAM",
                            'route' => 'paiement/imputation-siham',
                        ],
                        'import-numero-pec' => [
                            'label' => "Import des numéros de prise en charge",
                            'title' => "Importer les numéros de prise en charge pour automatiser le fichier de paie",
                            'route' => 'paiement/import-numero-pec',
                        ],
                    ],
                ],
                'pilotage' => [
                    'label' => 'Pilotage',
                    'title' => 'Pilotage',
                    'icon'  => 'fas fa-chart-line',
                    'route' => 'paiement/pilotage',
                    'pages' => [
                        'ecarts-etats' => [
                            'label'       => 'Ecarts d\'heures complémentaires (CSV)',
                            'title'       => 'Ecarts d\'heures complémentaires (CSV)',
                            'description' => 'Export CSV des HETD (ne porte que sur les heures complémentaires et non sur le service dû)',
                            'route'       => 'paiement/ecarts-etats',
                        ],
                    ],
                    'order' => 20,
                    'color' => '#00A020',
                ],
            ],
        ],
    ],


    'services' => [
        Service\MiseEnPaiementService::class                     => Service\MiseEnPaiementServiceFactory::class,
        Service\MiseEnPaiementIntervenantStructureService::class => Service\MiseEnPaiementIntervenantStructureServiceFactory::class,
        Service\TblPaiementService::class                        => Service\TblPaiementServiceFactory::class,
        Service\MotifNonPaiementService::class                   => Service\MotifNonPaiementServiceFactory::class,
        Service\NumeroPriseEnChargeService::class                => Service\NumeroPriseEnChargeServiceFactory::class,
        PaiementProcess::class                                   => PaiementProcessFactory::class,
    ],

    'view_helpers' => [
        'typeHeures' => View\Helper\TypeHeuresViewHelperFactory::class,
    ],

    'forms' => [
        Form\Paiement\MiseEnPaiementForm::class          => Form\Paiement\MiseEnPaiementFormFactory::class,
        Form\Paiement\MiseEnPaiementRechercheForm::class => Form\Paiement\MiseEnPaiementRechercheFormFactory::class,
    ],

    'controllers' => [
        Controller\PaiementController::class => Controller\PaiementControllerFactory::class,
    ],
];