<?php

namespace Lieu;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'structure' => [
            'route'         => '/structure',
            'controller'    => Controller\StructureController::class,
            'action'        => 'index',
            'privileges'    => Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'voir'   => [
                    'route'       => '/voir/:structure',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'controller'    => Controller\StructureController::class,
                    'action'      => 'voir',
                    'roles'       => ['user'],
                ],
                'saisie' => [
                    'route'       => '/saisie/[:structure]',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'controller'    => Controller\StructureController::class,
                    'action'      => 'saisie',
                    'privileges'  => Privileges::STRUCTURES_ADMINISTRATION_EDITION,
                    'assertion'   => Assertion\StructureAssertion::class,
                ],
                'delete' => [
                    'route'       => '/delete/:structure',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'controller'    => Controller\StructureController::class,
                    'action'      => 'delete',
                    'privileges'  => Privileges::STRUCTURES_ADMINISTRATION_EDITION,
                    'assertion'   => Assertion\StructureAssertion::class,
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'nomenclatures' => [
                    'pages' => [
                        'structure' => [
                            'label'    => 'Structures',
                            'route'    => 'structure',
                            'resource' => PrivilegeController::getResourceId(Controller\StructureController::class, 'index'),
                            'order'    => 40,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => Privileges::STRUCTURES_ADMINISTRATION_EDITION,
            'resources'  => 'Structure',
            'assertion'  => Assertion\StructureAssertion::class,
        ],
    ],

    'controllers' => [
        Controller\StructureController::class => Controller\StructureControllerFactory::class,
    ],

    'services' => [
        Service\StructureService::class     => Service\StructureServiceFactory::class,
        Assertion\StructureAssertion::class => AssertionFactory::class,
    ],

    'view_helpers' => [
        'structure' => View\Helper\StructureViewHelperFactory::class,
    ],

    'forms' => [
        Form\StructureSaisieForm::class => Form\StructureSaisieFormFactory::class,
    ],
];
