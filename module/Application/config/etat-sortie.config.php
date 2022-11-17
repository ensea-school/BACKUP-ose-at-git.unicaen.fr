<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'etat-sortie' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/etat-sortie',
                    'defaults' => [
                        'controller' => 'Application\Controller\EtatSortie',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'telecharger' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/telecharger/:etatSortie',
                            'constraints' => [
                                'etatSortie' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'telecharger',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'saisir'      => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisir[/:etatSortie]',
                            'constraints' => [
                                'etatSortie' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer'   => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:etatSortie',
                            'constraints' => [
                                'etatSortie' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'generer-pdf' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/generer/pdf/:etatSortie',
                            'constraints' => [
                                'etatSortie' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'generer-pdf',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'generer-csv' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/generer/csv/:etatSortie',
                            'constraints' => [
                                'etatSortie' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'generer-csv',
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
                            'configuration' => [
                                'pages' => [
                                    'etat-sortie' => [
                                        'color' => '#9F491F',
                                        'label'        => "États de sortie",
                                        'title'        => "États de sortie",
                                        'route'        => 'etat-sortie',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\EtatSortie', 'index'),
                                        'order'        => 30,
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
                    'controller' => 'Application\Controller\EtatSortie',
                    'action'     => ['index', 'telecharger', 'generer-pdf', 'generer-csv'],
                    'privileges' => [
                        Privileges::ETAT_SORTIE_ADMINISTRATION_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\EtatSortie',
                    'action'     => ['saisir', 'supprimer'],
                    'privileges' => [
                        Privileges::ETAT_SORTIE_ADMINISTRATION_EDITION,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\EtatSortie' => Controller\Factory\EtatSortieControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\EtatSortieService::class => Service\Factory\EtatSortieServiceFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\EtatSortieForm::class => Form\Factory\EtatSortieFormFactory::class,
        ],
    ],
];