<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'routes' => [
        'statut-intervenant' => [
            'options'       => [
                'route'       => '/statut-intervenant',
                'constraints' => [
                    'statutIntervenant' => '[0-9]*',
                ],
                'defaults'    => [
                    '__NAMESPACE__' => 'Application\Controller',
                    'controller'    => 'StatutIntervenant',
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'                   => [
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
                'delete'                   => [
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
                'statut-intervenant-trier' => [
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
                'clone'                    => [
                    'options'       => [
                        'route'       => '/clone/:statutIntervenant',
                        'constraints' => [
                            'statutIntervenant' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'clone',
                        ],
                    ],
                    'may_terminate' => true,
                ],
            ],
        ],
    ],

    'guards'          => [
        PrivilegeController::class => [
            [
                'controller' => 'Application\Controller\StatutIntervenant',
                'action'     => ['index', 'saisie'],
                'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
            ],
            [
                'controller' => 'Application\Controller\StatutIntervenant',
                'action'     => ['delete', 'statut-intervenant-trier', 'clone'],
                'privileges' => [Privileges::INTERVENANT_STATUT_EDITION],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationStatutIntervenant' => Service\StatutService::class,
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
