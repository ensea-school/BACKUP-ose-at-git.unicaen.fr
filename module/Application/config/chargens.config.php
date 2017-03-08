<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [

    /* Routes (à personnaliser) */
    'router'       => [
        'routes' => [
            'chargens' => [
                'type'          => 'Segment',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/chargens',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Chargens',
                        'action'        => 'index',
                    ],
                ],
                'child_routes'  => [
                    'json'        => [
                        'type'          => 'Literal',
                        'may_terminate' => false,
                        'options'       => [
                            'route' => '/json',
                        ],
                        'child_routes'  => [
                            'etape' => [
                                'type'          => 'Literal',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'    => '/etape',
                                    'defaults' => [
                                        'action' => 'etape-json',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'enregistrer' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/enregistrer',
                            'defaults' => [
                                'action' => 'enregistrer',
                            ],
                        ],
                    ],
                    'scenario' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/scenario',
                            'defaults' => [
                                'action' => 'scenario',
                            ],
                        ],
                        'child_routes'  => [
                            'saisir'    => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/saisir[/:scenario]',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'scenario-saisir',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'dupliquer'    => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/dupliquer[/:scenario]',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'scenario-dupliquer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/supprimer/:scenario',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'scenario-supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],

    /* Exemple de menu */
    'navigation'   => [
        'default' => [
            'home' => [
                'pages' => [
                    'chargens' => [
                        'label'    => "Charges",
                        'title'    => "Charges d'enseignement",
                        'route'    => 'chargens',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Chargens', 'index'),
                    ],
                ],
            ],
        ],
    ],

    /* Droits d'accès */
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['index', 'etape-json', 'enregistrer'],
                    'privileges' => [
                        Provider\Privilege\Privileges::CHARGENS_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['scenario'],
                    'privileges' => [
                        Provider\Privilege\Privileges::CHARGENS_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['scenario-saisir', 'scenario-dupliquer', 'scenario-supprimer'],
                    'privileges' => [
                        Provider\Privilege\Privileges::CHARGENS_VISUALISATION,
                    ],
                ],
            ],
        ],
    ],

    /* Déclaration du contrôleur */
    'controllers'  => [
        'factories' => [
            'Application\Controller\Chargens' => Controller\Factory\ChargensControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'applicationScenario' => Service\ScenarioService::class,
        ],
        'factories'  => [
            'chargens' => Provider\Chargens\ChargensProviderFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'chargens' => View\Helper\Chargens\ChargensViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'chargensFiltre' => Form\Chargens\FiltreForm::class,
            'chargensDuplicationScenario' => Form\Chargens\DuplicationScenarioForm::class,
            'chargensScenario' => Form\Chargens\ScenarioForm::class,
        ],
    ],
];