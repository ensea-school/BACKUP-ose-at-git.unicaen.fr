<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'router' => [
        'routes' => [
            'structure' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/structure',
                    'defaults' => [
                        'controller' => 'Application\Controller\Structure',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/voir/:structure',
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:structure',
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:structure]',
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
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
                            'nomenclatures' => [
                                'pages' => [
                                    'structure' => [
                                        'label'        => 'Structures',
                                        'route'        => 'structure',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Structure', 'index'),
                                        'order'        => 40,
                                        'color' => '#BBCF55',
                                    ],
                                ],
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
                    'controller' => 'Application\Controller\Structure',
                    'action'     => ['index'],
                    'privileges' => Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\Structure',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => Privileges::STRUCTURES_ADMINISTRATION_EDITION,
                    'assertion'  => Assertion\StructureAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Structure',
                    'action'     => ['voir'],
                    'roles'      => ['user'],
                ],
            ],
        ],

        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::STRUCTURES_ADMINISTRATION_EDITION,
                        'resources'  => 'Structure',
                        'assertion'  => Assertion\StructureAssertion::class,
                    ],
                ],
            ],
        ],

    ],

    'controllers'     => [
        'invokables' => [
            'Application\Controller\Structure' => Controller\StructureController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\StructureService::class => Service\StructureService::class,
        ],
        'factories'  => [
            Assertion\StructureAssertion::class => \UnicaenPrivilege\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'structure' => View\Helper\StructureViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\Structure\StructureSaisieForm::class => Form\Structure\StructureSaisieFormFactory::class,
        ],
    ],
];
