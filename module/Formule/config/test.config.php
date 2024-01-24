<?php

namespace Formule;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'formule-test' => [
            'route'         => '/formule-test',
            'controller'    => Controller\TestController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::FORMULE_TESTS],
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'          => [
                    'route'       => '/saisir[/:formuleTestIntervenant]',
                    'constraints' => [
                        'formuleTestIntervenant' => '[0-9]*',
                    ],
                    'controller'  => Controller\TestController::class,
                    'action'      => 'saisir',
                    'privileges'  => [Privileges::FORMULE_TESTS],
                ],
                'enregistrement'  => [
                    'route'       => '/enregistrement[/:formuleTestIntervenant]',
                    'constraints' => [
                        'formuleTestIntervenant' => '[0-9]*',
                    ],
                    'controller'  => Controller\TestController::class,
                    'action'      => 'enregistrement',
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
                            'resource' => PrivilegeController::getResourceId(Controller\TestController::class, 'index'),
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