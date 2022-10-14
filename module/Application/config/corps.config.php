<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'corps' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/corps',
                    'defaults' => [
                        'controller' => 'Application\Controller\Corps',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:corps]',
                            'constraints' => [
                                'corps' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:corps',
                            'constraints' => [
                                'corps' => '[0-9]*',
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

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'rh' => [
                                'pages' => [
                                    'corps' => [
                                        'label'        => 'Corps',
                                        'route'        => 'corps',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Corps', 'index'),
                                        'order'        => 10,
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
                    'controller' => 'Application\Controller\Corps',
                    'action'     => ['index'],
                    'privileges' => [Privileges::NOMENCLATURE_RH_CORPS_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Corps',
                    'action'     => ['saisie', 'supprimer'],
                    'privileges' => [Privileges::NOMENCLATURE_RH_CORPS_EDITION],
                ],
            ],
        ],
    ],
    'controllers'   => [
        'factories' => [
            'Application\Controller\Corps' => Controller\Factory\CorpsControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\Corps\CorpsSaisieForm::class => Form\Corps\CorpsSaisieFormFactory::class,
        ],
    ],
];
