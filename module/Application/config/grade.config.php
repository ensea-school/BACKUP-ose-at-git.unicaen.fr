<?php

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'grades' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/grades',
                    'defaults' => [
                        'controller' => 'Application\Controller\Grade',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:grade]',
                            'constraints' => [
                                'grade' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'delete' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/delete/:grade',
                            'constraints' => [
                                'grade' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'rh' => [
                                'pages' => [
                                    'grade' => [
                                        'label'        => 'Grades',
                                        'route'        => 'grades',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Grade', 'index'),
                                        'order'        => 30,
                                        'border-color' => '#BBCF55',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize'  => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Grade',
                    'action'     => ['index'],
                    'privileges' => [Privileges::NOMENCLATURE_RH_GRADES_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Grade',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => [Privileges::NOMENCLATURE_RH_GRADES_EDITION],
                ],


            ],
        ],
    ],
    'controllers'   => [
        'invokables' => [
            'Application\Controller\Grade' => Controller\GradeController::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\Grade\GradeSaisieForm::class => Form\Grade\GradeSaisieForm::class,
        ],
    ],
];
