<?php

namespace Paiement;

use Application\Provider\Privileges;

return [
    'routes' => [
        'budget' => [
            'route'         => '/budget',
            'controller'    => Controller\BudgetController::class,
            'action'        => 'index',
            'privileges'    => [
                Privileges::BUDGET_VISUALISATION,
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'engagements-liquidation' => [
                    'route'       => '/engagements-liquidation[/:structure]',
                    'controller'  => Controller\BudgetController::class,
                    'action'      => 'engagements-liquidation',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                ],
                'tableau-de-bord'         => [
                    'route'      => '/tableau-de-bord',
                    'controller' => Controller\BudgetController::class,
                    'action'     => 'tableau-de-bord',
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                    'assertion'  => \Paiement\Assertion\BudgetAssertion::class,
                ],
                'export'                  => [
                    'route'       => '/export[/:structure]',
                    'controller'  => Controller\BudgetController::class,
                    'action'      => 'export',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => [
                        Privileges::BUDGET_EXPORT,
                    ],
                ],
                'get-json'                => [
                    'route'       => '/get-json[/:structure]',
                    'controller'  => Controller\BudgetController::class,
                    'action'      => 'get-json',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => [
                        Privileges::BUDGET_VISUALISATION,
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    ],
                ],
                'get-budget-structure'    => [
                    'route'       => '/get-budget-structure[/:structure]',
                    'controller'  => Controller\BudgetController::class,
                    'action'      => 'get-budget-structure',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => [
                        Privileges::BUDGET_VISUALISATION,
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    ],
                ],
                'saisie-dotation'         => [
                    'route'       => '/saisie-dotation/:annee/:structure/:typeRessource/[:dotation1]/[:dotation2]',
                    'controller'  => Controller\BudgetController::class,
                    'action'      => 'saisie-dotation',
                    'constraints' => [
                        'annee'         => '[0-9]*',
                        'structure'     => '[0-9]*',
                        'typeRessource' => '[0-9]*',
                        //'dotation1'     => '[0-9]*',
                        //'dotation2'     => '[0-9]*',
                    ],
                    'privileges'  => [
                        Privileges::BUDGET_EDITION_ENGAGEMENT_COMPOSANTE,
                        Privileges::BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'gestion' => [
            'pages' => [
                'budget' => [
                    'label'    => 'Budget',
                    'title'    => 'Budget',
                    'icon'     => 'fas fa-eur',
                    'route'    => 'budget',
                    'order'    => 30,
                    'color'    => '#EB4995',
                    'pages'    => [
                        'tableau-de-bord'         => [
                            'label'    => 'Tableau de bord',
                            'title'    => 'Tableau de bord',
                            'route'    => 'budget/tableau-de-bord',
                        ],
                        'engagements-liquidation' => [
                            'label'    => 'Engagements & liquidation',
                            'title'    => 'Engagements & liquidation',
                            'route'    => 'budget/engagements-liquidation',
                        ],
                        'export'                  => [
                            'label'    => 'Export des données de paiement (CSV)',
                            'title'    => 'Export des données de paiement (CSV)',
                            'route'    => 'budget/export',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\BudgetController::class,
            'action'     => ['type-dotation'],
            'privileges' => [Privileges::BUDGET_TYPE_DOTATION_VISUALISATION],
        ],
        [
            'controller' => Controller\BudgetController::class,
            'action'     => ['type-dotation-saisie', 'type-dotation-delete'],
            'privileges' => [Privileges::BUDGET_TYPE_DOTATION_EDITION],
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::BUDGET_EDITION_ENGAGEMENT_COMPOSANTE,
                Privileges::BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT,
            ],
            'resources'  => ['Dotation', 'Structure', 'TypeRessource'],
            'assertion'  => Assertion\BudgetAssertion::class,
        ],
    ],

    'forms' => [
        Form\Budget\DotationSaisieForm::class => Form\Budget\DotationSaisieFormFactory::class,
    ],

    'services' => [
        Service\TypeRessourceService::class => Service\TypeRessourceServiceFactory::class,
        Service\DotationService::class      => Service\DotationServiceFactory::class,
        Service\BudgetService::class        => Service\BudgetServiceFactory::class,
    ],

    'controllers' => [
        Controller\BudgetController::class => Controller\BudgetControllerFactory::class,
    ],
];