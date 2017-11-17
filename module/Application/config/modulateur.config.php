<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'modulateur' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/modulateur',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Modulateur',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'saisie' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/saisie/:typeModulateur[/:modulateur]',
                            'constraints' => [
                                'modulateur' => '[0-9]*',
                                'typeModulateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'delete' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/delete/:modulateur',
                            'constraints' => [
                                'modulateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-modulateur-saisie' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/type-modulateur-saisie[/:typeModulateur]',
                            'constraints' => [
                                'typeModulateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'type-modulateur-saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-modulateur-delete' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/type-modulateur-delete/:typeModulateur',
                            'constraints' => [
                                'typeModulateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'type-modulateur-delete',
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
                            'modulateur' => [
                                'label' => 'Modulateur',
                                'title' => 'Modulateurs',
                                'icon' => 'fa fa-tachometer',
                                'route' => 'modulateur',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Modulateur', 'index'),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Modulateur',
                    'action' => ['index'],
                    'privileges' => [Privileges::MODULATEUR_VISUALISATION],
                    'assertion' => 'assertionModulateur',
                ],
                [
                    'controller' => 'Application\Controller\Modulateur',
                    'action' => ['saisie', 'delete', 'type-modulateur-saisie', 'type-modulateur-delete'],
                    'privileges' => [Privileges::MODULATEUR_EDITION],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'TypeModulateur' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::MODULATEUR_VISUALISATION,
                        'resources' => ['TypeModulateur', 'StructureService'],
                        'assertion' => 'assertionModulateur',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'assertionModulateur' => Assertion\ModulateurAssertion::class,
            'applicationTypeModulateurStructure' => Service\TypeModulateurStructureService::class,
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Modulateur' => Controller\ModulateurController::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'ModulateurSaisie' => Form\Modulateur\ModulateurSaisieForm::class,
            'TypeModulateurSaisie' => Form\Modulateur\TypeModulateurSaisieForm::class,
        ],
    ],
];
