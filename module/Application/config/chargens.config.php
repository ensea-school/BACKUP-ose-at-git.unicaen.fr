<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [

    /* Routes (à personnaliser) */
    'router'       => [
        'routes' => [
            'chargens' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/chargens[/:etape][/:scenario]',
                    'constraints' => [
                        'structure' => '[0-9]*',
                        'etape'     => '[0-9]*',
                        'scenario'  => '[0-9]*',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Chargens',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    /* Placez ici vos routes filles */
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
                    'action'     => ['index'],
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
    'form_elements'     => [
        'invokables' => [
            'chargensFiltre' => Form\Chargens\FiltreForm::class,
        ],
    ],
];