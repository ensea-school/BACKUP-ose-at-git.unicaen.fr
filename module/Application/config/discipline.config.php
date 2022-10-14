<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'discipline' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/discipline',
                    'defaults' => [
                        'controller' => 'Application\Controller\Discipline',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir'      => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/voir/:discipline',
                            'constraints' => [
                                'discipline' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'voir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'saisir'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisir[/:discipline]',
                            'constraints' => [
                                'discipline' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:discipline',
                            'constraints' => [
                                'discipline' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
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
                            'odf' => [
                                'pages' => [
                                    'discipline' => [
                                        'border-color' => '#9F491F',
                                        'icon'         => 'fas fa-table-list',
                                        'label'        => "Disciplines",
                                        'title'        => "Gestion des disciplines",
                                        'route'        => 'discipline',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Discipline', 'index'),
                                        'order'        => 70,
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
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::DISCIPLINE_GESTION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['voir'],
                    'privileges' => [
                        Privileges::DISCIPLINE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['saisir', 'supprimer'],
                    'privileges' => [
                        Privileges::DISCIPLINE_EDITION,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Discipline' => Controller\DisciplineController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\DisciplineService::class => Service\DisciplineService::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\DisciplineForm::class => Form\DisciplineForm::class,
        ],
    ],
];