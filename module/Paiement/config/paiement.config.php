<?php

namespace Paiement;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
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
                    'action'      => 'MiseEnPaiement',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                ],
                'etat-paiement'         => [
                    'route'      => '/etat-paiement',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'etatPaiement',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                    ],
                ],
                'mises-en-paiement-csv' => [
                    'route'      => '/mises-en-paiement-csv',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'misesEnPaiementCsv',
                ],
                'extraction-paie'       => [
                    'route'      => '/extraction-paie[/:type][/:periode]',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'extractionPaie',
                ],
                'imputation-siham'      => [
                    'route'      => '/imputation-siham',
                    'controller' => Controller\PaiementController::class,
                    'action'     => 'imputationSiham',
                    'defaults'   => [
                        'etat' => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                    ],
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
                    'visible'             => \Paiement\Assertion\PaiementAssertion::class,
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
                    'visible'             => \Paiement\Assertion\PaiementAssertion::class,
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
                    'visible'      => \Paiement\Assertion\PaiementAssertion::class,
                    'order'        => 18,
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

                        'imputation-siham' => [
                            'label'    => "Imputation budgétaire SIHAM",
                            'title'    => "Export des données pour chargement en masse des imputations budgétaires dans SIHAM",
                            'route'    => 'paiement/imputation-siham',
                            'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE),
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\PaiementController::class,
            'action'     => ['demandeMiseEnPaiement'],
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
            'action'     => ['extractionPaie', 'imputationSiham'],
            'privileges' => [Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE],
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
        Service\CentreCoutService::class                         => Service\CentreCoutServiceFactory::class,
        Service\MotifNonPaiementService::class                   => Service\MotifNonPaiementServiceFactory::class,
        Assertion\PaiementAssertion::class                       => \UnicaenPrivilege\Assertion\AssertionFactory::class,
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