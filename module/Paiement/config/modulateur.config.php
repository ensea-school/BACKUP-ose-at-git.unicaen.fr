<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use Paiement\Service\TypeModulateurService;
use UnicaenPrivilege\Guard\PrivilegeController;

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
                    'action'      => 'saisie',
                    'constraints' => [
                        'modulateur'     => '[0-9]*',
                        'typeModulateur' => '[0-9]*',
                    ],
                ],
                'delete'                           => [
                    'route'       => '/delete/:modulateur',
                    'action'      => 'delete',
                    'constraints' => [
                        'modulateur' => '[0-9]*',
                    ],
                ],
                'type-modulateur-saisie'           => [
                    'route'       => '/type-modulateur-saisie[/:typeModulateur]',
                    'constraints' => [
                        'typeModulateur' => '[0-9]*',
                    ],
                    'action'      => 'type-modulateur-saisie',
                ],
                'type-modulateur-delete'           => [
                    'route'       => '/type-modulateur-delete/:typeModulateur',
                    'constraints' => [
                        'typeModulateur' => '[0-9]*',
                    ],
                    'action'      => 'type-modulateur-delete',
                ],
                'type-modulateur-structure-saisie' => [
                    'route'       => '/type-modulateur-structure-saisie/:typeModulateur[/:typeModulateurStructure]',
                    'constraints' => [
                        'typeModulateur'          => '[0-9]*',
                        'typeModulateurStructure' => '[0-9]*',
                    ],
                    'action'      => 'type-modulateur-structure-saisie',
                ],
                'type-modulateur-structure-delete' => [
                    'route'       => '/type-modulateur-structure-delete/:typeModulateurStructure',
                    'constraints' => [
                        'typeModulateurStructure' => '[0-9]*',
                    ],
                    'action'      => 'type-modulateur-structure-delete',
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
                            'resource' => PrivilegeController::getResourceId(Controller\ModulateurController::class, 'index'),
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        PrivilegeController::class => [
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
    ],

    'rules' => [
        [
            'privileges' => Privileges::MODULATEUR_VISUALISATION,
            'resources'  => ['TypeModulateur', 'Structure'],
            'assertion'  => Assertion\ModulateurAssertion::class,
        ],
    ],

    'services' => [
        'invokables'                         => [
            \Paiement\Service\TypeModulateurStructureService::class => \Paiement\Service\TypeModulateurStructureService::class,
            \Paiement\Service\ModulateurService::class              => \Paiement\Service\ModulateurService::class,
            TypeModulateurService::class                            => TypeModulateurService::class,

        ],
        Assertion\ModulateurAssertion::class => \UnicaenPrivilege\Assertion\AssertionFactory::class,
    ],

    'controllers' => [
        'invokables' => [
            Controller\ModulateurController::class => Controller\ModulateurController::class,
        ],
    ],

    'forms' => [
        'invokables' => [
            \Paiement\Form\Modulateur\ModulateurSaisieForm::class              => \Paiement\Form\Modulateur\ModulateurSaisieForm::class,
            Form\Modulateur\TypeModulateurSaisieForm::class                    => Form\Modulateur\TypeModulateurSaisieForm::class,
            \Paiement\Form\Modulateur\TypeModulateurStructureSaisieForm::class => \Paiement\Form\Modulateur\TypeModulateurStructureSaisieForm::class,
        ],
    ],
];
