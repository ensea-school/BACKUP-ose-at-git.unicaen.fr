<?php

namespace Paiement;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'modulateur' => [
            'route'         => '/modulateur',
            'controller'    => Controller\ModulateurController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'                           => [
                    'route'       => '/saisie/:typeModulateur[/:modulateur]',
                    'controller'    => Controller\ModulateurController::class,
                    'action'      => 'saisie',
                    'constraints' => [
                        'modulateur'     => '[0-9]*',
                        'typeModulateur' => '[0-9]*',
                    ],
                ],
                'delete'                           => [
                    'route'       => '/delete/:modulateur',
                    'controller'    => Controller\ModulateurController::class,
                    'action'      => 'delete',
                    'constraints' => [
                        'modulateur' => '[0-9]*',
                    ],
                ],
                'type-modulateur-saisie'           => [
                    'route'       => '/type-modulateur-saisie[/:typeModulateur]',
                    'controller'    => Controller\ModulateurController::class,
                    'action'      => 'type-modulateur-saisie',
                    'constraints' => [
                        'typeModulateur' => '[0-9]*',
                    ],
                ],
                'type-modulateur-delete'           => [
                    'route'       => '/type-modulateur-delete/:typeModulateur',
                    'controller'    => Controller\ModulateurController::class,
                    'action'      => 'type-modulateur-delete',
                    'constraints' => [
                        'typeModulateur' => '[0-9]*',
                    ],
                ],
                'type-modulateur-structure-saisie' => [
                    'route'       => '/type-modulateur-structure-saisie/:typeModulateur[/:typeModulateurStructure]',
                    'controller'    => Controller\ModulateurController::class,
                    'action'      => 'type-modulateur-structure-saisie',
                    'constraints' => [
                        'typeModulateur'          => '[0-9]*',
                        'typeModulateurStructure' => '[0-9]*',
                    ],
                ],
                'type-modulateur-structure-delete' => [
                    'route'       => '/type-modulateur-structure-delete/:typeModulateurStructure',
                    'controller'    => Controller\ModulateurController::class,
                    'action'      => 'type-modulateur-structure-delete',
                    'constraints' => [
                        'typeModulateurStructure' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'odf' => [
                    'pages' => [
                        'modulateur' => [
                            'label'    => 'Modulateurs des taux horaires',
                            'route'    => 'modulateur',
                            'order'    => 10,
                            'resource' => Authorize::controllerResource(Controller\ModulateurController::class, 'index'),
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\ModulateurController::class,
            'action'     => ['index'],
            'privileges' => [Privileges::MODULATEUR_VISUALISATION],
            'assertion'  => Assertion\ModulateurAssertion::class,
        ],
        [
            'controller' => Controller\ModulateurController::class,
            'action'     => ['saisie', 'delete', 'type-modulateur-saisie', 'type-modulateur-delete', 'type-modulateur-structure-saisie', 'type-modulateur-structure-delete'],
            'privileges' => [Privileges::MODULATEUR_EDITION],
        ],
    ],

    'rules' => [
        [
            'privileges' => Privileges::MODULATEUR_VISUALISATION,
            'resources'  => ['TypeModulateur', 'Structure'],
            'assertion'  => Assertion\ModulateurAssertion::class,
        ],
        [
            'privileges' => Privileges::MODULATEUR_EDITION,
            'resources'  => ['TypeModulateurStructure'],
            'assertion'  => Assertion\ModulateurAssertion::class,
        ],
    ],

    'services' => [
        Service\TypeModulateurStructureService::class => Service\TypeModulateurStructureServiceFactory::class,
        Service\ModulateurService::class              => Service\ModulateurServiceFactory::class,
        Service\TypeModulateurService::class          => Service\TypeModulateurServiceFactory::class,
    ],

    'controllers' => [
        Controller\ModulateurController::class => Controller\ModulateurControllerFactory::class,
    ],

    'forms' => [
        Form\Modulateur\ModulateurSaisieForm::class              => Form\Modulateur\ModulateurSaisieFormFactory::class,
        Form\Modulateur\TypeModulateurSaisieForm::class          => Form\Modulateur\TypeModulateurSaisieFormFactory::class,
        Form\Modulateur\TypeModulateurStructureSaisieForm::class => Form\Modulateur\TypeModulateurStructureSaisieFormFactory::class,
    ],
];
