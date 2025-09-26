<?php

namespace Formule;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'formule-test' => [
            'route'         => '/formule-test',
            'controller'    => Controller\TestController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::FORMULE_TESTS],
            'may_terminate' => true,
            'child_routes'  => [
                'data'            => [
                    'route'      => '/data',
                    'controller' => Controller\TestController::class,
                    'action'     => 'indexData',
                    'privileges' => [Privileges::FORMULE_TESTS],
                ],
                'saisir'          => [
                    'route'       => '/saisir[/:formuleTestIntervenant]',
                    'constraints' => [
                        'formuleTestIntervenant' => '[0-9]*',
                    ],
                    'controller'  => Controller\TestController::class,
                    'action'      => 'saisir',
                    'privileges'  => [Privileges::FORMULE_TESTS],
                ],
                'saisir-data'            => [
                    'route'      => '/saisir-data[/:formuleTestIntervenant]',
                    'constraints' => [
                        'formuleTestIntervenant' => '[0-9]*',
                    ],
                    'controller' => Controller\TestController::class,
                    'action'     => 'saisirData',
                    'privileges' => [Privileges::FORMULE_TESTS],
                ],
                'enregistrer'  => [
                    'route'       => '/enregistrer[/:formuleTestIntervenant]',
                    'constraints' => [
                        'formuleTestIntervenant' => '[0-9]*',
                    ],
                    'controller'  => Controller\TestController::class,
                    'action'      => 'enregistrer',
                    'privileges'  => [Privileges::FORMULE_TESTS],
                ],
                'import'          => [
                    'route'      => '/import',
                    'controller' => Controller\TestController::class,
                    'action'     => 'import',
                    'privileges' => [Privileges::FORMULE_TESTS],
                ],
                'supprimer'       => [
                    'route'       => '/supprimer/:formuleTestIntervenant',
                    'constraints' => [
                        'formuleTestIntervenant' => '[0-9]*',
                    ],
                    'controller'  => Controller\TestController::class,
                    'action'      => 'supprimer',
                    'privileges'  => [Privileges::FORMULE_TESTS],
                ],
                'creer-from-reel' => [
                    'route'       => '/creer-from-reel/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                        'etatVolumeHoraire' => '[0-9]*',
                    ],
                    'controller'  => Controller\TestController::class,
                    'action'      => 'creer-from-reel',
                    'privileges'  => [Privileges::FORMULE_TESTS],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'formule-calcul' => [
                            'label'    => 'Test de formule de calcul',
                            'route'    => 'formule-test',
                            'resource' => Authorize::controllerResource(Controller\TestController::class, 'index'),
                            'order'    => 50,
                            'color'    => '#0C8758',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\TestController::class => Controller\TestControllerFactory::class,
    ],

    'services' => [
        Service\TestService::class => Service\TestServiceFactory::class,
    ],
];