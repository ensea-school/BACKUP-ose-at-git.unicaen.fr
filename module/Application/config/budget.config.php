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
                        'controller' => 'Application\Controller\Budget',
                        'action'     => 'index',
                    ],
                ],
                'child_routes'  => [
                    'engagements-liquidation' => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/engagements-liquidation[/:structure]',
                            'defaults'    => [
                                'action' => 'engagements-liquidation',
                            ],
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                        ],
                    ],
                    'tableau-de-bord'         => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/tableau-de-bord',
                            'defaults' => [
                                'action' => 'tableau-de-bord',
                            ],
                        ],
                    ],
                    'export'                  => [
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
                    'get-json'                => [
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
                    'saisie-dotation'         => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/saisie-dotation/:annee/:structure/:typeRessource/[:dotation1]/[:dotation2]',
                            'defaults'    => [
                                'action' => 'saisie-dotation',
                            ],
                            'constraints' => [
                                'annee'         => '[0-9]*',
                                'structure'     => '[0-9]*',
                                'typeRessource' => '[0-9]*',
                                //'dotation1'     => '[0-9]*',
                                //'dotation2'     => '[0-9]*',
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
                                'icon'         => 'fas fa-eur',
                                'route'        => 'budget',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Budget', 'index'),
                                'order'        => 30,
                                'color' => '#EB4995',
                                'pages'        => [
                                    'tableau-de-bord'         => [
                                        'label'    => 'Tableau de bord',
                                        'title'    => 'Tableau de bord',
                                        'route'    => 'budget/tableau-de-bord',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Budget', 'tableau-de-bord'),
                                    ],
                                    'engagements-liquidation' => [
                                        'label'    => 'Engagements & liquidation',
                                        'title'    => 'Engagements & liquidation',
                                        'route'    => 'budget/engagements-liquidation',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Budget', 'engagements-liquidation'),
                                    ],
                                    'export'                  => [
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
        'guards'         => [
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
                    'action'     => ['tableau-de-bord'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                    'assertion'  => Assertion\BudgetAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['engagements-liquidation'],
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
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
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
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['type-dotation'],
                    'privileges' => [Privileges::BUDGET_TYPE_DOTATION_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['type-dotation-saisie', 'type-dotation-delete'],
                    'privileges' => [Privileges::BUDGET_TYPE_DOTATION_EDITION],
                ],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::BUDGET_EDITION_ENGAGEMENT_COMPOSANTE,
                            Privileges::BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT,
                        ],
                        'resources'  => ['Dotation', 'Structure', 'TypeRessource'],
                        'assertion'  => Assertion\BudgetAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Budget\DotationSaisieForm::class => Form\Budget\DotationSaisieForm::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\TypeRessourceService::class => Service\TypeRessourceService::class,
            Service\DotationService::class      => Service\DotationService::class,
        ],
        'factories'  => [
            Assertion\BudgetAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Budget' => Controller\BudgetController::class,
        ],
    ],
];