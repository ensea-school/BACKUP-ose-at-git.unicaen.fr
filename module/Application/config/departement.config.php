<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'router' => [
        'routes' => [
            'departement' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/departement',
                    'defaults' => [
                        'controller' => 'Application\Controller\Departement',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/saisie[/:departement]',
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/supprimer/:departement',
                            'defaults' => [
                                'action' => 'supprimer',
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
                                    'gestion-departement' => [
                                        'label'          => 'Départements',
                                        'icon'           => 'fas fa-table-list',
                                        'route'          => 'departement',
                                        'resource'       => PrivilegeController::getResourceId('Application\Controller\Departement', 'index'),
                                        'order'          => 10,
                                        'border - color' => '#111',
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
                    'controller' => 'Application\Controller\Departement',
                    'action'     => ['index'],
                    'privileges' => [Privileges::PARAMETRES_DEPARTEMENT_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Departement',
                    'action'     => ['saisie', 'supprimer'],
                    'privileges' => [Privileges::PARAMETRES_DEPARTEMENT_EDITION],
                ],
            ],
        ],

    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\Departement' => Controller\Factory\DepartementControllerFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\Departement\DepartementSaisieForm::class => Form\Departement\DepartementSaisieFormFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\DepartementService::class => Service\DepartementService::class,
        ],
    ],
];
