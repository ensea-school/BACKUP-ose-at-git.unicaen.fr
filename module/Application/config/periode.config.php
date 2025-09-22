<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application;


use Application\Provider\Privilege\Privileges;
use Application\Service\PeriodeService;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'periodes' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/periodes',
                    'defaults' => [
                        'controller' => 'Application\Controller\Periode',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:periode]',
                            'constraints' => [
                                'periode' => '[0-9]*',
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
                            'route'       => '/supprimer/:periode',
                            'constraints' => [
                                'periode' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'trier'     => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'      => '/trier',
                            'constraints' => [
                            ],
                            'defaults'   => [
                                'action' => 'trier',
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
                            'finances' => [
                                'pages' => [
                                    'gestion-periode' => [
                                        'label'          => 'Périodes',
                                        'route'          => 'periodes',
                                        'resource'       => PrivilegeController::getResourceId('Application\Controller\Periode', 'index'),
                                        'order'          => 30,
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

    'bjyauthorize'  => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Periode',
                    'action'     => ['index'],
                    'privileges' => [Privileges::PARAMETRES_PERIODES_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Periode',
                    'action'     => ['saisie', 'supprimer', 'trier'],
                    'privileges' => [Privileges::PARAMETRES_PERIODES_EDITION],
                ],
            ],
        ],
    ],
    'controllers'   => [
        'factories' => [
            'Application\Controller\Periode' => Controller\Factory\PeriodeControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            PeriodeService::class => PeriodeService::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\Periode\PeriodeSaisieForm::class => Form\Periode\PeriodeSaisieFormFactory::class,
        ],
    ],
];
