<?php

namespace Application;

use Application\Entity\Db\TypeAgrement;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'intervenant' => [
                'child_routes' => [
                    'agrement' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/:intervenant/agrement',
                            'defaults' => [
                                'controller' => 'Application\Controller\Agrement',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'conseil-academique' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/conseil-academique',
                                    'defaults' => [
                                        'action'           => 'lister',
                                        'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_ACADEMIQUE,
                                    ],
                                ],
                            ],
                            'conseil-restreint'  => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/conseil-restreint',
                                    'defaults' => [
                                        'action'           => 'lister',
                                        'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_RESTREINT,
                                    ],
                                ],
                            ],
                            'ajouter'            => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/:typeAgrement/ajouter[/:structure]',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                        'structure'    => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'saisir',
                                    ],
                                ],
                            ],
                            'voir'               => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/voir/:agrement',
                                    'constraints' => [
                                        'agrement' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'voir',
                                    ],
                                ],
                            ],
                            'saisir'             => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/saisir/[:agrement]',
                                    'constraints' => [
                                        'agrement' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'saisir',
                                    ],
                                ],
                            ],
                            'supprimer'          => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/supprimer/[:agrement]',
                                    'constraints' => [
                                        'agrement' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'gestion'     => [
                'child_routes' => [
                    'agrement' => [
                        'type'          => 'Literal',
                        'options'       => [
                            'route'    => '/agrement',
                            'defaults' => [
                                'controller' => 'Application\Controller\Agrement',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'conseil-academique' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/conseil-academique',
                                    'defaults' => [
                                        'action'           => 'saisir-lot',
                                        'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_ACADEMIQUE,
                                    ],
                                ],
                            ],
                            'conseil-restreint'  => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/conseil-restreint',
                                    'defaults' => [
                                        'action'           => 'saisir-lot',
                                        'typeAgrementCode' => TypeAgrement::CODE_CONSEIL_RESTREINT,
                                    ],
                                ],
                            ],
                            'export-csv'         => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/export-csv',
                                    'defaults' => [
                                        'action' => 'export-csv',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'intervenant' => [
                        'pages' => [
                            'agrement-conseil-restreint'  => [
                                'label'        => 'Agrément : Conseil restreint',
                                'title'        => 'Agrément : Conseil restreint',
                                'route'        => 'intervenant/agrement/conseil-restreint',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Agrement', 'lister'),
                                'visible'      => Assertion\AgrementAssertion::class,
                                'order'        => 10,
                            ],
                            'agrement-conseil-academique' => [
                                'label'        => 'Agrément : Conseil académique',
                                'title'        => 'Agrément : Conseil académique',
                                'route'        => 'intervenant/agrement/conseil-academique',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Agrement', 'lister'),
                                'visible'      => Assertion\AgrementAssertion::class,
                                'order'        => 11,
                            ],
                        ],
                    ],
                    'gestion'     => [
                        'pages' => [
                            'agrement' => [
                                'label'        => "Agréments par lot",
                                'title'        => "Gestion des agréments par lot",
                                'icon'         => 'fas fa-tags',
                                'route'        => 'gestion/agrement',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Agrement', 'index'),
                                'visible'      => Assertion\AgrementAssertion::class,
                                'order'        => 50,
                                'border-color' => '#E1AC5A',
                                'pages'        => [
                                    'conseil-restreint'  => [
                                        'label'       => 'Conseil restreint',
                                        'description' => 'Gestion par lots des agréments du conseil restreint',
                                        'title'       => 'Conseil restreint',
                                        'route'       => 'gestion/agrement/conseil-restreint',
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Agrement', 'saisir-lot'),
                                        'visible'     => Assertion\AgrementAssertion::class,
                                    ],
                                    'conseil-academique' => [
                                        'label'       => 'Conseil académique',
                                        'description' => 'Gestion par lots des agréments du conseil académique',
                                        'title'       => 'Conseil académique',
                                        'route'       => 'gestion/agrement/conseil-academique',
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Agrement', 'saisir-lot'),
                                        'visible'     => Assertion\AgrementAssertion::class,
                                    ],
                                    'export-csv'         => [
                                        'label'       => 'Export CSV',
                                        'description' => 'Export CSV des agrément donnés ou en attente',
                                        'title'       => 'Export CSV',
                                        'route'       => 'gestion/agrement/export-csv',
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Agrement', 'export-csv'),
                                    ],
                                ],
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
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => ['index', 'lister', 'voir'],
                    'privileges' => [
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
                    ],
                    'assertion'  => Assertion\AgrementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => ['ajouter', 'saisir-lot', 'saisir'],
                    'privileges' => [
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION,
                    ],
                    'assertion'  => Assertion\AgrementAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => ['export-csv'],
                    'privileges' => [
                        Privileges::AGREMENT_EXPORT_CSV,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => ['supprimer'],
                    'privileges' => [
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION,
                    ],
                    'assertion'  => Assertion\AgrementAssertion::class,
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Agrement'    => [],
                'TblAgrement' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION,
                            Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION,
                            Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION,
                            Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION,
                        ],
                        'resources'  => ['TblAgrement', 'Agrement', 'Structure'],
                        'assertion'  => Assertion\AgrementAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Agrement' => Controller\AgrementController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\AgrementService::class           => Service\AgrementService::class,
            Service\TblAgrementService::class        => Service\TblAgrementService::class,
            Service\TypeAgrementService::class       => Service\TypeAgrementService::class,
            Service\TypeAgrementStatutService::class => Service\TypeAgrementStatutService::class,
        ],
        'factories'  => [
            Assertion\InformationAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
            Assertion\AgrementAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'agrement' => View\Helper\AgrementViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Agrement\Saisie::class => Form\Agrement\Saisie::class,
        ],
    ],
];
