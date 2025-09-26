<?php

namespace Paiement;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'centre-cout-activite' => [
            'route'         => '/centre-cout-activite',
            'controller'    => Controller\CentreCoutController::class,
            'action'        => 'centre-cout-activite',
            'may_terminate' => true,
            'child_routes'  => [
                'delete' => [
                    'route'       => '/delete/:ccActivite',
                    'controller'  => Controller\CentreCoutController::class,
                    'action'      => 'centre-cout-activite-delete',
                    'constraints' => [
                        'ccActivite' => '[0-9]*',
                    ],
                ],
                'saisie' => [
                    'route'       => '/saisie/[:ccActivite]',
                    'controller'  => Controller\CentreCoutController::class,
                    'action'      => 'centre-cout-activite-saisie',
                    'constraints' => [
                        'ccActivite' => '[0-9]*',
                    ],
                ],
            ],
        ],

        'centre-cout' => [
            'route'         => '/centre-cout',
            'controller'    => Controller\CentreCoutController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'delete'           => [
                    'route'       => '/delete/:centreCout',
                    'controller'  => Controller\CentreCoutController::class,
                    'action'      => 'delete',
                    'constraints' => [
                        'centreCout' => '[0-9]*',
                    ],
                ],
                'saisie'           => [
                    'route'       => '/saisie/[:centreCout]',
                    'controller'  => Controller\CentreCoutController::class,
                    'action'      => 'saisie',
                    'constraints' => [
                        'centreCout' => '[0-9]*',
                    ],
                ],
                'delete-structure' => [
                    'route'       => '/delete-structure/:centreCoutStructure',
                    'controller'  => Controller\CentreCoutController::class,
                    'action'      => 'delete-structure',
                    'constraints' => [
                        'centreCoutStructure' => '[0-9]*',
                    ],
                ],
                'saisie-structure' => [
                    'route'       => '/saisie-structure/:centreCout/[:centreCoutStructure]',
                    'controller'  => Controller\CentreCoutController::class,
                    'action'      => 'saisie-structure',
                    'constraints' => [
                        'centreCout'          => '[0-9]*',
                        'centreCoutStructure' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'finances' => [
                    'pages' => [
                        'centre-cout'          => [
                            'label'    => 'Centres de coûts',
                            'route'    => 'centre-cout',
                            'resource' => Authorize::controllerResource(Controller\CentreCoutController::class, 'index'),
                            'order'    => 10,
                            'color'    => '#BBCF55',
                        ],
                        'centre-cout-activite' => [
                            'label'    => 'Types d\'activités des centres de coûts',
                            'route'    => 'centre-cout-activite',
                            'resource' => Authorize::controllerResource(Controller\CentreCoutController::class, 'index'),
                            'order'    => 40,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\CentreCoutController::class,
            'action'     => ['index', 'centre-cout-activite'],
            'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
        ],
        [
            'controller' => Controller\CentreCoutController::class,
            'action'     => ['saisie', 'delete', 'saisie-structure', 'delete-structure', 'centre-cout-activite-delete', 'centre-cout-activite-saisie'],
            'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION,
        ],
    ],

    'rules' => [
        [
            'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION,
            'resources'  => 'CentreCoutStructure',
            'assertion'  => Assertion\CentreCoutAssertion::class,
        ],
    ],

    'controllers' => [
        Controller\CentreCoutController::class => Controller\CentreCoutControllerFactory::class,
    ],

    'services' => [
        Assertion\CentreCoutAssertion::class      => \Framework\Authorize\AssertionFactory::class,
        Service\CentreCoutService::class          => Service\CentreCoutServiceFactory::class,
        Service\CentreCoutStructureService::class => Service\CentreCoutStructureServiceFactory::class,
        Service\CcActiviteService::class          => Service\CcActiviteServiceFactory::class,
    ],

    'forms' => [
        Form\CentreCout\CentreCoutSaisieForm::class          => Form\CentreCout\CentreCoutSaisieFormFactory::class,
        Form\CentreCout\CentreCoutStructureSaisieForm::class => Form\CentreCout\CentreCoutStructureSaisieFormFactory::class,
        Form\CentreCout\CentreCoutActiviteSaisieForm::class  => Form\CentreCout\CentreCoutActiviteSaisieFormFactory::class,
    ],
];
