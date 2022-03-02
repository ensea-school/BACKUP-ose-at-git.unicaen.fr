<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [

    /* Déclaration du contrôleur */
    'controllers' => [
        'factories' => [
            'Application\Controller\Formule' => Controller\Factory\FormuleControllerFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'formule-calcul' => [
                'type'         => 'Literal',
                'options'      => [
                    'route'    => '/formule-calcul',
                    'defaults' => [
                        'controller' => 'Application\Controller\Formule',
                        //'action'        => 'index',
                    ],
                ],
                'child_routes' => [
                    'test' => [
                        'type'          => 'Literal',
                        'options'       => [
                            'route'    => '/test',
                            'defaults' => [
                                'action' => 'test',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'saisir'          => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/saisir[/:formuleTestIntervenant]',
                                    'constraints' => [
                                        'formuleTestIntervenant' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'test-saisir',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'enregistrement'  => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/enregistrement[/:formuleTestIntervenant]',
                                    'constraints' => [
                                        'formuleTestIntervenant' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'test-enregistrement',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer'       => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/supprimer/:formuleTestIntervenant',
                                    'constraints' => [
                                        'formuleTestIntervenant' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'test-supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'creer-from-reel' => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/creer-from-reel/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                                    'constraints' => [
                                        'typeVolumeHoraire' => '[0-9]*',
                                        'etatVolumeHoraire' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'test-creer-from-reel',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'formule-calcul' => [
                    'options' => [
                        'route'    => 'formule-calcul',
                        'defaults' => [
                            'controller' => 'Application\Controller\Formule',
                            'action'     => 'calculer-tout',
                        ],
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
                            'formule-calcul' => [
                                'label'        => 'Test de formule de calcul',
                                'icon'         => 'fas fa-briefcase',
                                'route'        => 'formule-calcul/test',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Formule', 'test'),
                                'order'        => 80,
                                'border-color' => '#0C8758',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Formule',
                    'action'     => ['test', 'test-saisir', 'test-enregistrement', 'test-supprimer', 'test-creer-from-reel'],
                    'privileges' => [Privileges::FORMULE_TESTS],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories'  => [
            Service\FormuleService::class                => Service\Factory\FormuleServiceFactory::class,
            Service\FormuleTestIntervenantService::class => Service\Factory\FormuleTestIntervenantServiceFactory::class,
        ],
        'invokables' => [
            Service\FormuleResultatService::class                         => Service\FormuleResultatService::class,
            Service\FormuleResultatServiceService::class                  => Service\FormuleResultatServiceService::class,
            Service\FormuleResultatServiceReferentielService::class       => Service\FormuleResultatServiceReferentielService::class,
            Service\FormuleResultatVolumeHoraireService::class            => Service\FormuleResultatVolumeHoraireService::class,
            Service\FormuleResultatVolumeHoraireReferentielService::class => Service\FormuleResultatVolumeHoraireReferentielService::class,
        ],
    ],

];