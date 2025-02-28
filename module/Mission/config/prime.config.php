<?php

namespace Mission;

use Application\Provider\Privilege\Privileges;
use Mission\Controller\PrimeController;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Workflow\Entity\Db\WfEtape;


return [
    'routes' => [
        'prime' => [
            'route'         => '/prime',
            'may_terminate' => false,
            'child_routes'  => [
                'liste' => [
                    'route'      => '/:intervenant/liste',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'liste',
                    'privileges' => Privileges::MISSION_PRIME_VISUALISATION,
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
                    'privileges' => Privileges::MISSION_PRIME_GESTION,


                ],
                'devalider-declaration-prime'   => [
                    'route'      => '/:intervenant/devalider-declaration-prime/:prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'devalider-declaration-prime',
                    'privileges' => Privileges::MISSION_PRIME_GESTION,


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
                    'privileges' => Privileges::MISSION_PRIME_GESTION,


                ],
                'supprimer-prime'               => [
                    'route'      => '/:intervenant/supprimer-prime/[:prime]',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'supprimer-prime',
                    'privileges' => Privileges::MISSION_PRIME_GESTION,


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
                    'assertion'  => Assertion\PrimeAssertion::class,

                ],
            ],

        ],

    ],


    'navigation' => [
        'intervenant' => [
            'pages' => [
                'prime' => [
                    'label'               => "Indemnités de fin de contrat",
                    'title'               => "Indemnités de fin de contrat",
                    'route'               => 'intervenant/prime-mission',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_MISSION_PRIME,
                    'withtarget'          => true,
                    'resource'            => PrivilegeController::getResourceId(Controller\PrimeController::class, 'index'),
                    'order'               => 14,
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => PrimeController::class,
            'action'     => ['supprimer-prime', 'saisie', 'valider-declaration-prime', 'devalider-declaration-prime'],
            'privileges' => [
                'privileges' => Privileges::MISSION_PRIME_GESTION,
            ],
            'assertion'  => Assertion\PrimeAssertion::class,

        ],
        [
            'controller' => PrimeController::class,
            'action'     => ['index', 'declaration-prime', 'supprimer-declaration-prime', 'telecharger-declaration-prime', 'refuser-prime', 'liste'],
            'privileges' => [
                'privileges' => Privileges::MISSION_PRIME_VISUALISATION,
            ],
            'assertion'  => Assertion\PrimeAssertion::class,

        ],

    ],


    'controllers' => [
        Controller\PrimeController::class => Controller\PrimeControllerFactory::class,
    ],

    'services' => [
        Service\PrimeService::class     => Service\PrimeServiceFactory::class,
        Assertion\PrimeAssertion::class => AssertionFactory::class,

    ],

];
