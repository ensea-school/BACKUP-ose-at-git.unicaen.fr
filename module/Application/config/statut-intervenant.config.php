<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'statut-intervenant' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/statut-intervenant',
                    'constraints' => [
                        'statutIntervenant' => '[0-9]*',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'StatutIntervenant',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:statutIntervenant]',
                            'constraints' => [
                                'statutIntervenant' => '[0-9]*',
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
                            'route'       => '/delete/:statutIntervenant',
                            'constraints' => [
                                'statutIntervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'statut-intervenant-trier'           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'      => '/statut-intervenant-trier',
                            'contraints' => [
                            ],
                            'defaults'   => [
                                'action' => 'statut-intervenant-trier',
                            ],
                        ],
                        'may_terminate' => 'true',
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
                            'statut-intervenant' => [
                                'label'        => 'Statuts Intervenants',
                                'title'        => 'Satuts Intervenants',
                                'icon'         => 'fa fa-graduation-cap',
                                'route'        => 'statut-intervenant',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\StatutIntervenant', 'index'),
                                'order'        => 90,
                                'border-color' => '#BBCF55',
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
                    'controller' => 'Application\Controller\StatutIntervenant',
                    'action'     => ['index','saisie'],
                    'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\StatutIntervenant',
                    'action'     => ['delete','statut-intervenant-trier'],
                    'privileges' => [Privileges::INTERVENANT_STATUT_EDITION],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationStatutIntervenant' => Service\StatutIntervenant::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\StatutIntervenant' => Controller\StatutIntervenantController::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'statutIntervenantSaisie' => Form\StatutIntervenant\StatutIntervenantSaisieForm::class,
        ],
    ],
];
