<?php

namespace Application;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'paiement' => [
                'type'          => 'Literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/paiement',
                    'defaults' => [
                        'controller' => 'Application\Controller\Paiement',
                        'action'     => 'index',
                    ],
                ],
                'child_routes'  => [
                    'etat-demande-paiement' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/etat-demande-paiement',
                            'defaults' => [
                                'action' => 'etatPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::A_METTRE_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'mise-en-paiement'      => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/mise-en-paiement/:structure/:intervenants',
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'MiseEnPaiement',
                            ],
                        ],
                    ],
                    'etat-paiement'         => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/etat-paiement',
                            'defaults' => [
                                'action' => 'etatPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'mises-en-paiement-csv' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/mises-en-paiement-csv',
                            'defaults' => [
                                'action' => 'misesEnPaiementCsv',
                            ],
                        ],
                    ],
                    'extraction-winpaie'    => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/extraction-winpaie[/:type][/:periode]',
                            'defaults' => [
                                'action' => 'extractionWinpaie',
                            ],
                        ],
                    ],
                    'imputation-siham'      => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/imputation-siham',
                            'defaults' => [
                                'action' => 'imputationSiham',
                                'etat'   => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,

                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
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
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Paiement', 'demandeMiseEnPaiement'),
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
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Paiement', 'visualisationMiseEnPaiement'),
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
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Paiement', 'editionMiseEnPaiement'),
                                'visible'      => Assertion\PaiementAssertion::class,
                                'order'        => 18,
                            ],
                        ],
                    ],
                    'gestion'     => [
                        'pages' => [
                            'paiement' => [
                                'label'        => "Paiement",
                                'title'        => "Paiement",
                                'route'        => 'paiement',
                                'icon'         => 'fas fa-credit-card',
                                'border-color' => '#F5E79E',
                                'resource'     => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                                'order'        => 40,
                                'pages'        => [
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
                                    'extraction-winpaie'    => [
                                        'label'    => "Extraction Winpaie",
                                        'title'    => "Export des données de paiement au format Winpaie",
                                        'route'    => 'paiement/extraction-winpaie',
                                        'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE),
                                    ],
                                    'imputation-siham'      => [
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
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['demandeMiseEnPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    ],
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['visualisationMiseEnPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT,
                    ],
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['editionMiseEnPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_EDITION,
                    ],
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['etatPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                    ],
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['miseEnPaiement'],
                    'privileges' => [Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION],
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['misesEnPaiementCsv'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_EXPORT_CSV,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['extractionWinpaie', 'imputationSiham'],
                    'privileges' => [Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'MiseEnPaiement' => [],
                'TypeRessource'  => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                        'resources'  => 'MiseEnPaiement',
                        'assertion'  => Assertion\PaiementAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\ServiceAPayerService::class                      => Service\ServiceAPayerService::class,
            Service\MiseEnPaiementService::class                     => Service\MiseEnPaiementService::class,
            Service\MiseEnPaiementIntervenantStructureService::class => Service\MiseEnPaiementIntervenantStructureService::class,
            Service\TypeHeuresService::class                         => Service\TypeHeuresService::class,
            Service\CentreCoutService::class                         => Service\CentreCoutService::class,
            Service\CentreCoutEpService::class                       => Service\CentreCoutEpService::class,
        ],
        'factories'  => [
            Assertion\PaiementAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'demandeMiseEnPaiement' => View\Helper\Paiement\DemandeMiseEnPaiementViewHelper::class,
            'typeHeures'            => View\Helper\Paiement\TypeHeuresViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Paiement\MiseEnPaiementForm::class          => Form\Paiement\MiseEnPaiementForm::class,
            Form\Paiement\MiseEnPaiementRechercheForm::class => Form\Paiement\MiseEnPaiementRechercheForm::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Paiement' => Controller\PaiementController::class,
        ],
    ],
];