<?php

namespace Mission;

use Application\Provider\Privilege\Privileges;
use Mission\Controller\PrimeController;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'routes' => [
        'prime' => [
            'route'         => '/prime',
            'may_terminate' => false,
            'child_routes'  => [
                'liste'                         => [
                    'route'      => '/:intervenant/liste',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'liste',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,
                ],
                'get-contrat-prime'             => [
                    'route'      => '/:intervenant/get-contrat-prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'get-contrat-prime',

                ],
                'declaration-prime'             => [
                    'route'      => '/:intervenant/declaration-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'declaration-prime',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,


                ],
                'supprimer-declaration-prime'   => [
                    'route'      => '/:intervenant/supprimer-declaration-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'supprimer-declaration-prime',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,


                ],
                'valider-declaration-prime'     => [
                    'route'      => '/:intervenant/valider-declaration-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'valider-declaration-prime',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,


                ],
                'devalider-declaration-prime'   => [
                    'route'      => '/:intervenant/devalider-declaration-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'devalider-declaration-prime',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,


                ],
                'refuser-prime'                 => [
                    'route'      => '/:intervenant/refuser-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'refuser-prime',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,


                ],
                'telecharger-declaration-prime' => [
                    'route'      => '/:intervenant/telecharger-declaration-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'telecharger-declaration-prime',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,


                ],
                'saisie'                        => [
                    'route'      => '/:intervenant/saisie/[:prime]',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'saisie',

                ],
                'supprimer-prime'               => [
                    'route'      => '/:intervenant/supprimer-prime/[:prime]',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'supprimer-prime',

                ],
            ],
        ],

        'intervenant' => [
            'child_routes' => [
                'prime-mission' => [
                    'route'      => '/:intervenant/prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'index',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,
                ],
            ],

        ],

    ],


    'navigation' => [
        'intervenant' => [
            'pages' => [
                'prime' => [
                    'label'        => "Prime de fin de missions",
                    'title'        => "Prime de fin de missions",
                    'route'        => 'intervenant/prime-mission',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => PrivilegeController::getResourceId(Controller\PrimeController::class, 'index'),
                    'order'        => 14,
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => PrimeController::class,
            'action'     => ['supprimer-prime', 'saisie', 'index', 'get-contrat-prime', 'declaration-prime', 'supprimer-declaration-prime', 'telecharger-declaration-prime', 'refuser-prime', 'liste', 'valider-declaration-prime', 'devalider-declaration-prime'],
            'privileges' => [
                Privileges::MISSION_PRIME_VISUALISATION,
            ],
        ],
        /* [
             'controller' => PrimeController::class,
             'action'     => ['valider-declaration-prime', 'devalider-declaration-prime'],
             'privileges' => [
                 Privileges::MISSION_PRIME_VALIDER,
             ],
         ],*/

    ],


    'controllers' => [
        Controller\PrimeController::class => Controller\PrimeControllerFactory::class,
    ],

    'services' => [
        Service\PrimeService::class => Service\PrimeServiceFactory::class,
    ],

];
