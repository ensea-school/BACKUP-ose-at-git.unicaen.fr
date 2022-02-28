<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'modulateur' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/modulateur',
                    'defaults' => [
                        'controller' => 'Application\Controller\Modulateur',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'                           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie/:typeModulateur[/:modulateur]',
                            'constraints' => [
                                'modulateur'     => '[0-9]*',
                                'typeModulateur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'delete'                           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/delete/:modulateur',
                            'constraints' => [
                                'modulateur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-modulateur-saisie'           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-modulateur-saisie[/:typeModulateur]',
                            'constraints' => [
                                'typeModulateur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-modulateur-saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-modulateur-delete'           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-modulateur-delete/:typeModulateur',
                            'constraints' => [
                                'typeModulateur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-modulateur-delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-modulateur-structure-saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-modulateur-structure-saisie/:typeModulateur[/:typeModulateurStructure]',
                            'constraints' => [
                                'typeModulateur'          => '[0-9]*',
                                'typeModulateurStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-modulateur-structure-saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-modulateur-structure-delete' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-modulateur-structure-delete/:typeModulateurStructure',
                            'constraints' => [
                                'typeModulateurStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-modulateur-structure-delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'modulateur' => [
                                'label'    => 'Modulateurs des taux horaires',
                                'icon'     => 'fa-solid fa-tachometer',
                                'route'    => 'modulateur',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Modulateur', 'index'),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Modulateur',
                    'action'     => ['index'],
                    'privileges' => [Privileges::MODULATEUR_VISUALISATION],
                    'assertion'  => Assertion\ModulateurAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Modulateur',
                    'action'     => ['saisie', 'delete', 'type-modulateur-saisie', 'type-modulateur-delete', 'type-modulateur-structure-saisie', 'type-modulateur-structure-delete'],
                    'privileges' => [Privileges::MODULATEUR_EDITION],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'TypeModulateur' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::MODULATEUR_VISUALISATION,
                        'resources'  => ['TypeModulateur', 'Structure'],
                        'assertion'  => Assertion\ModulateurAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\TypeModulateurStructureService::class => Service\TypeModulateurStructureService::class,
        ],
        'factories' => [
            Assertion\ModulateurAssertion::class          => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Modulateur' => Controller\ModulateurController::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Modulateur\ModulateurSaisieForm::class              => Form\Modulateur\ModulateurSaisieForm::class,
            Form\Modulateur\TypeModulateurSaisieForm::class          => Form\Modulateur\TypeModulateurSaisieForm::class,
            Form\Modulateur\TypeModulateurStructureSaisieForm::class => Form\Modulateur\TypeModulateurStructureSaisieForm::class,
        ],
    ],
];
