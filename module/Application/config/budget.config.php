<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'budget' => [
                'type'          => 'Literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/budget',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Budget',
                        'action'        => 'index',
                    ],
                ],
                'child_routes'  => [
                    'engagement'      => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/engagement[/:structure]',
                            'defaults'    => [
                                'action' => 'engagement',
                            ],
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                        ],
                    ],
                    'liquidation'     => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/liquidation',
                            'defaults' => [
                                'action' => 'liquidation',
                            ],
                        ],
                    ],
                    'export'          => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/export[/:structure]',
                            'defaults'    => [
                                'action' => 'export',
                            ],
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                        ],
                    ],
                    'get-json'        => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/get-json[/:structure]',
                            'defaults'    => [
                                'action' => 'get-json',
                            ],
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                        ],
                    ],
                    'saisie-dotation' => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/saisie-dotation/:annee/:structure/:typeRessource[/:dotation1/:dotation2]',
                            'defaults'    => [
                                'action' => 'saisie-dotation',
                            ],
                            'constraints' => [
                                'annee'         => '[0-9]*',
                                'structure'     => '[0-9]*',
                                'typeRessource' => '[0-9]*',
                                'dotation1'     => '[0-9]*',
                                'dotation2'     => '[0-9]*',
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
                    'gestion' => [
                        'pages' => [
                            'budget' => [
                                'label'        => 'Budget',
                                'title'        => 'Budget',
                                'icon'         => 'fa fa-eur',
                                'border-color' => '#A22CAE',
                                'route'        => 'budget',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Budget', 'index'),

                                'pages' => [
                                    'engagement'  => [
                                        'label'    => 'Engagement',
                                        'title'    => 'Engagement',
                                        'route'    => 'budget/engagement',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Budget', 'engagement'),
                                    ],
                                    'liquidation' => [
                                        'label'    => 'Liquidation',
                                        'title'    => 'Liquidation',
                                        'route'    => 'budget/liquidation',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Budget', 'liquidation'),
                                    ],
                                    'export'      => [
                                        'label'    => 'Export des données de paiement (CSV)',
                                        'title'    => 'Export des données de paiement (CSV)',
                                        'route'    => 'budget/export',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Budget', 'export'),
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
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['liquidation'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                    'assertion'  => 'assertionBudget',
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['engagement'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['export'],
                    'privileges' => [
                        Privileges::BUDGET_EXPORT,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['get-json'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['saisie-dotation'],
                    'privileges' => [
                        Privileges::BUDGET_EDITION_ENGAGEMENT_COMPOSANTE,
                        Privileges::BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT,
                    ],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'Dotation' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::BUDGET_EDITION_ENGAGEMENT_COMPOSANTE,
                            Privileges::BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT,
                        ],
                        'resources'  => ['Dotation', 'Structure', 'TypeRessource'],
                        'assertion'  => 'assertionBudget',
                    ],
                ],
            ],
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'BudgetDotationSaisie' => Form\Budget\DotationSaisieForm::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'applicationTypeRessource' => Service\TypeRessourceService::class,
            'applicationDotation'      => Service\DotationService::class,
            'assertionBudget'          => Assertion\BudgetAssertion::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Budget' => Controller\BudgetController::class,
        ],
    ],
];