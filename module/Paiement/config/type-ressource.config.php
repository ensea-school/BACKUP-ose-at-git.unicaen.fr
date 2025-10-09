<?php

namespace Paiement;

use Application\Provider\Privileges;

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
                    'controller'    => Controller\TypeRessourceController::class,
                    'action'      => 'saisie',
                    'constraints' => [
                        'typeRessource' => '[0-9]*',
                    ],
                ],
                'delete' => [
                    'route'       => '/delete[/:typeRessource]',
                    'controller'    => Controller\TypeRessourceController::class,
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
        Service\TypeRessourceService::class => Service\TypeRessourceServiceFactory::class,
    ],

    'controllers' => [
        Controller\TypeRessourceController::class => Controller\TypeRessourceControllerFactory::class,
    ],

    'forms' => [
        Form\TypeRessource\TypeRessourceSaisieForm::class => Form\TypeRessource\TypeRessourceSaisieFormFactory::class,
    ],
];
