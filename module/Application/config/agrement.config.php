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
                            'route'       => '/:intervenant/agrement',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'Agrement',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'liste'    => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/:typeAgrement',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'lister',
                                    ],
                                ],
                            ],
                            'ajouter'  => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/:typeAgrement/ajouter[/:structure]',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                        'structure' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'saisir',
                                    ],
                                ],
                            ],
                            'voir'     => [
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
                            'saisir' => [
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
                            'supprimer' => [
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
                                'controller' => 'Agrement',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'saisir-lot' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/:typeAgrement/saisir-lot',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'saisir-lot',
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
                                'route'        => 'intervenant/agrement/liste',
                                'params'       => [
                                    'typeAgrement' => TypeAgrement::CONSEIL_RESTREINT_ID,
                                ],
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Agrement', 'lister'),
                                'visible'      => 'AssertionAgrement',
                            ],
                            'agrement-conseil-academique' => [
                                'label'        => 'Agrément : Conseil académique',
                                'title'        => 'Agrément : Conseil académique',
                                'route'        => 'intervenant/agrement/liste',
                                'params'       => [
                                    'typeAgrement' => TypeAgrement::CONSEIL_ACADEMIQUE_ID,
                                ],
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Agrement', 'lister'),
                                'visible'      => 'AssertionAgrement',
                            ],
                        ],
                    ],
                    'gestion'     => [
                        'pages' => [
                            'agrement' => [
                                'label'        => "Agréments par lot",
                                'title'        => "Gestion des agréments par lot",
                                'icon'         => 'fa fa-tags',
                                'border-color' => '#E1AC5A',
                                'route'        => 'gestion/agrement',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Agrement', 'index'),
                                'visible'      => 'AssertionAgrement',
                                'pages'        => [
                                    'conseil-restreint'  => [
                                        'label'       => 'Conseil restreint',
                                        'description' => 'Gestion par lots des agréments du conseil restreint',
                                        'title'       => 'Conseil restreint',
                                        'route'       => 'gestion/agrement/saisir-lot',
                                        'params'      => [
                                            'typeAgrement' => TypeAgrement::CONSEIL_RESTREINT_ID,
                                        ],
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Agrement', 'saisir-lot'),
                                        'visible'     => 'AssertionAgrement',
                                    ],
                                    'conseil-academique' => [
                                        'label'       => 'Conseil académique',
                                        'description' => 'Gestion par lots des agréments du conseil académique',
                                        'title'       => 'Conseil académique',
                                        'route'       => 'gestion/agrement/saisir-lot',
                                        'params'      => [
                                            'typeAgrement' => TypeAgrement::CONSEIL_ACADEMIQUE_ID,
                                        ],
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Agrement', 'saisir-lot'),
                                        'visible'     => 'AssertionAgrement',
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
                    'assertion'  => 'AssertionAgrement',
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
                    'assertion'  => 'AssertionAgrement',
                ],
                [
                    'controller' => 'Application\Controller\Agrement',
                    'action' => ['supprimer'],
                    'privileges' => [
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION,
                    ],
                    'assertion'  => 'AssertionAgrement',
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
                            Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION
                        ],
                        'resources'  => ['TblAgrement', 'Agrement', 'Structure'],
                        'assertion'  => 'AssertionAgrement',
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
            'ApplicationAgrement'           => Service\Agrement::class,
            'ApplicationTblAgrement'        => Service\TblAgrementService::class,
            'ApplicationTypeAgrement'       => Service\TypeAgrement::class,
            'ApplicationTypeAgrementStatut' => Service\TypeAgrementStatut::class,
            'AssertionAgrement'             => Assertion\AgrementAssertion::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'agrement' => View\Helper\AgrementViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'AgrementSaisieForm' => Form\Agrement\Saisie::class,
        ],
    ],
];
