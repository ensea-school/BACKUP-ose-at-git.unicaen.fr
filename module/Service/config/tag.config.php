<?php

namespace Service;

use Application\Provider\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Service\Controller\TagController;

return [
    'routes' => [
        'tag' => [
            'type'          => 'Literal',
            'options'       => [
                'route'    => '/tags',
                'defaults' => [
                    'controller' => TagController::class,
                    'action'     => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'supprimer' => [
                    'type'    => 'Segment',
                    'options' => [
                        'route'       => '/supprimer/:tag',
                        'constraints' => [
                            'tag' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'supprimer',
                        ],
                    ],
                ],
                'saisir'    => [
                    'type'    => 'Segment',
                    'options' => [
                        'route'       => '/saisir/[:tag]',
                        'constraints' => [
                            'tag' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'saisir',
                        ],
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
                        'tag' => [
                            'label'    => 'Tags',
                            'route'    => 'tag',
                            'order'    => 50,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => TagController::class,
            'action'     => ['index'],
            'privileges' => Privileges::TAG_ADMINISTRATION_VISUALISATION,
        ],
        [
            'controller' => TagController::class,
            'action'     => ['saisir', 'supprimer'],
            'privileges' => Privileges::TAG_ADMINISTRATION_EDITION,
        ],
    ],

    'controllers' => [
        TagController::class => Controller\TagControllerFactory::class,
    ],

    'services' => [
        Service\TagService::class => InvokableFactory::class,
    ],

    'forms' => [
        Form\TagSaisieForm::class => InvokableFactory::class,

    ],
];