<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'tag' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/tags',
                    'defaults' => [
                        'controller' => 'Application\Controller\Tag',
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
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'finances' => [
                                'pages' => [
                                    'tag' => [
                                        'label'    => 'Tags',
                                        'route'    => 'tag',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Tag', 'index'),
                                        'order'    => 50,
                                        'color'    => '#BBCF55',
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
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Tag',
                    'action'     => ['index'],
                    //TODO ajouter le privilege pour gestion des tags
                    'privileges' => Privileges::TAG_ADMINISTRATION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\Tag',
                    'action'     => ['saisir', 'supprimer'],
                    //TODO ajouter le privilege pour gestion des tags
                    'privileges' => Privileges::TAG_ADMINISTRATION_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Tag' => Controller\TagController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\TagService::class => Service\TagService::class,
        ],
    ],
    'view_helpers'    => [
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Tag\TagSaisieForm::class => Form\Tag\TagSaisieForm::class,
        ],
    ],
];
