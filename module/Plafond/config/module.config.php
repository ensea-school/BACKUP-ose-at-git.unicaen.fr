<?php

namespace Plafond;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;

return [

    'routes' => [
        'type'          => 'Literal',
        'options'       => [
            'route'    => '/plafond',
            'defaults' => [
                'controller' => 'Plafond\Controller\Plafond',
                'action'     => 'index',
            ],
        ],
        'may_terminate' => true,
        'child_routes'  => [
            'ajouter'   => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/ajouter',
                    'defaults' => [
                        'action' => 'editer',
                    ],
                ],
            ],
            'modifier'  => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/modifier/:plafond',
                    'constraints' => [
                        'plafond' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'action' => 'editer',
                    ],
                ],
            ],
            'supprimer' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/supprimer/:plafond',
                    'constraints' => [
                        'plafond' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'action' => 'supprimer',
                    ],
                ],
            ],

            'editer-application'    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/editer-application[/:plafondApplication]',
                    'constraints' => [
                        'plafondApplication' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'action' => 'editerApplication',
                    ],
                ],
            ],
            'supprimer-application' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/supprimer-application/:plafondApplication',
                    'constraints' => [
                        'plafondApplication' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'action' => 'supprimerApplication',
                    ],
                ],
            ],

            'construire-calculer' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/construire-calculer',
                    'defaults' => [
                        'action' => 'construire-calculer',
                    ],
                ],
            ],

            'structure' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/structure/:structure',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => 'Plafond\Controller\PlafondStructure',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'ajouter'  => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/ajouter',
                            'defaults' => [
                                'action' => 'editer',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/modifier/:plafondStructure',
                            'constraints' => [
                                'plafondStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'editer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'construire' => [
            'options' => [
                'route'    => 'plafonds construire',
                'defaults' => [
                    'controller' => 'Plafond\Controller\Plafond',
                    'action'     => 'construire',
                ],
            ],
        ],
        'calculer'   => [
            'options' => [
                'route'    => 'plafonds calculer',
                'defaults' => [
                    'controller' => 'Plafond\Controller\Plafond',
                    'action'     => 'calculer',
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'plafonds' => [
                    'icon'         => 'glyphicon glyphicon-wrench',
                    'label'        => "Plafonds",
                    'route'        => 'plafond',
                    'resource'     => PrivilegeController::getResourceId('Plafond\Controller\Plafond', 'index'),
                    'border-color' => '#9B9B9B',
                    'order'        => 120,
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_VISUALISATION,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['editer', 'supprimer', 'construire', 'calculer', 'construire-calculer'],
            'privileges' => Privileges::PLAFONDS_EDITION,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['editerApplication', 'supprimerApplication'],
            'privileges' => Privileges::PLAFONDS_APPLICATION,
        ],
        [
            'controller' => 'Plafond\Controller\PlafondStructure',
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_STRUCTURE_VISUALISATION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\PlafondStructure',
            'action'     => ['editer'],
            'privileges' => Privileges::PLAFONDS_STRUCTURE_EDITION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::PLAFONDS_STRUCTURE_VISUALISATION,
                Privileges::PLAFONDS_STRUCTURE_EDITION,
            ],
            'resources'  => 'Structure',
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
    ],

    'controllers' => [
        'Plafond\Controller\Plafond'          => Controller\PlafondControllerFactory::class,
        'Plafond\Controller\PlafondStructure' => Controller\PlafondStructureControllerFactory::class,
    ],

    'services' => [
        Assertion\PlafondAssertion::class        => AssertionFactory::class,
        Service\PlafondApplicationService::class => Service\PlafondApplicationServiceFactory::class,
        Service\PlafondService::class            => Service\PlafondServiceFactory::class,
        Service\PlafondStructureService::class   => Service\PlafondStructureServiceFactory::class,
        Processus\PlafondProcessus::class        => Processus\PlafondProcessusFactory::class,
    ],

    'forms' => [
        Form\PlafondApplicationForm::class => Form\PlafondApplicationFormFactory::class,
        Form\PlafondForm::class            => Form\PlafondFormFactory::class,
        Form\PlafondStructureForm::class   => Form\PlafondStructureFormFactory::class,
    ],
];