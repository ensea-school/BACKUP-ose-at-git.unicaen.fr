<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'type-ressource' => [
            'route'         => '/type-ressource',
            'controller'    => Controller\TypeRessourceController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'route'       => '/saisie[/:typeRessource]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'typeRessource' => '[0-9]*',
                    ],
                ],
                'delete' => [
                    'route'       => '/delete[/:typeRessource]',
                    'action'      => 'delete',
                    'constraints' => [
                        'typeRessource' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'finances' => [
                    'pages' => [
                        'type-ressource' => [
                            'label'    => 'Types de ressources',
                            'route'    => 'type-ressource',
                            'resource' => PrivilegeController::getResourceId(Controller\TypeRessourceController::class, 'index'),
                            'order'    => 50,
                            'color'    => '#71DFD7',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\TypeRessourceController::class,
            'action'     => ['index'],
            'privileges' => [Privileges::TYPE_RESSOURCE_VISUALISATION],
        ],
        [
            'controller' => Controller\TypeRessourceController::class,
            'action'     => ['saisie', 'delete'],
            'privileges' => [Privileges::TYPE_RESSOURCE_EDITION],
        ],
    ],

    'services' => [
        'invokables' => [
            Service\TypeRessourceService::class => Service\TypeRessourceService::class,
        ],
    ],

    'controllers' => [
        'invokables' => [
            Controller\TypeRessourceController::class => Controller\TypeRessourceController::class,
        ],
    ],

    'forms' => [
        'invokables' => [
            Form\TypeRessource\TypeRessourceSaisieForm::class => Form\TypeRessource\TypeRessourceSaisieForm::class,
        ],
    ],
];
