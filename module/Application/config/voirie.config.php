<?php

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'voirie' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/voirie',
                    'defaults' => [
                        'controller' => 'Application\Controller\Voirie',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:voirie]',
                            'constraints' => [
                                'voirie' => '[0-9]*',
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
                            'route'       => '/delete/:voirie',
                            'constraints' => [
                                'voirie' => '[0-9]*',
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
                            'nomenclatures' => [
                                'pages' => [
                                    'voirie' => [
                                        'label'        => 'Voiries',
                                        'route'        => 'voirie',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Voirie', 'index'),
                                        'order'        => 50,
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
                    'controller' => 'Application\Controller\Voirie',
                    'action'     => ['index'],
                    'privileges' => [Privileges::REFERENTIEL_COMMUN_VOIRIE_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Voirie',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => [Privileges::REFERENTIEL_COMMUN_VOIRIE_EDITION],
                ],


            ],
        ],
    ],
    'controllers'   => [
        'invokables' => [
            'Application\Controller\Voirie' => Controller\VoirieController::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\Voirie\VoirieSaisieForm::class => Form\Voirie\VoirieSaisieForm::class,
        ],
    ],
];
