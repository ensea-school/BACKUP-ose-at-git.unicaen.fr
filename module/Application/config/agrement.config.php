<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Controller\AgrementController;

return [
    'router' => [
        'routes' => [
            'intervenant' => [
                'child_routes' => [
                    'agrement' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant/agrement',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'controller' => 'Agrement',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'liste' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/:typeAgrement',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'lister',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/:typeAgrement/ajouter',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => AgrementController::ACTION_AJOUTER,
                                    ],
                                ],
                            ],
                            'voir' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/voir/:agrement',
                                    'constraints' => [
                                        'agrement' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => AgrementController::ACTION_VOIR,
                                    ],
                                ],
                            ],
                            'voir-str' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/:typeAgrement/voir-str[/:structure]',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                        'structure' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => AgrementController::ACTION_VOIR_STR,
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/modifier/:agrement',
                                    'constraints' => [
                                        'agrement' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => AgrementController::ACTION_MODIFIER,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'gestion' => [
                'child_routes' => [
                    'agrement' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route' => '/agrement',
                            'defaults' => [
                                'controller' => 'Agrement',
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter-lot' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/:typeAgrement/ajouter-lot',
                                    'constraints' => [
                                        'typeAgrement' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => AgrementController::ACTION_AJOUTER_LOT,
                                    ],
                                ],
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
                    'intervenant' => [
                        'pages' => [
                            'agrement' => [
                                'label'         => "Agrément",
                                'title'         => "Agrément de l'intervenant",
                                'route'         => 'intervenant/agrement',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'    => true,
                                'resource'      => 'controller/Application\Controller\Agrement:index',
                                'visible'       => 'IntervenantNavigationPageVisibility',
                                'pagesProvider' => [
                                    'type'       => 'AgrementIntervenantNavigationPagesProvider',
                                    'route'      => 'intervenant/agrement/liste',
                                    'paramsInject' => [
                                        'intervenant',
                                    ],
                                    'withtarget' => true,
                                    'resource'   => 'controller/Application\Controller\Agrement:lister',
                                    'visible'    => 'IntervenantNavigationPageVisibility',
                                ],
                            ],
                        ],
                    ],
                    'gestion' => [
                        'pages' => [
                            'agrement' => [
                                'label'  => "Agréments par lot",
                                'title'  => "Gestion des agréments par lot",
                                'route'  => 'gestion/agrement',
                                'resource' => 'controller/Application\Controller\Agrement:index',
                                'pagesProvider' => [
                                    'type'  => 'AgrementNavigationPagesProvider',
                                    'route' => 'gestion/agrement/ajouter-lot',
                                    'withtarget' => true,
                                    'resource'   => 'controller/Application\Controller\Agrement:' . AgrementController::ACTION_AJOUTER_LOT,
                                    'privilege'  => AgrementController::ACTION_AJOUTER_LOT,
                                    // NB: le code du type d'agrément sera concaténé au 'privilege' par le AgrementNavigationPagesProvider
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
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => ['index', 'lister', 'voir'],
                    'roles'      => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                    'assertion'  => 'AgrementAssertion',
                ],
                [
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => ['ajouter', 'ajouter-lot', 'modifier', 'supprimer', 'voir-str'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                    'assertion'  => 'AgrementAssertion',
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Agrement' => [],
            ],
        ],
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                        'Agrement',
                        ['create', 'read', 'delete', 'update'],
                        'AgrementAssertion',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Agrement' => 'Application\Controller\AgrementController',
        ],
        'initializers' => [
            'Application\Service\Initializer\AgrementServiceAwareInitializer',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationAgrement'                        => 'Application\\Service\\Agrement',
            'ApplicationTypeAgrement'                    => 'Application\\Service\\TypeAgrement',
            'ApplicationTypeAgrementStatut'              => 'Application\\Service\\TypeAgrementStatut',
            'AgrementNavigationPagesProvider'            => 'Application\\Service\\AgrementNavigationPagesProvider',
            'AgrementIntervenantNavigationPagesProvider' => 'Application\\Service\\AgrementIntervenantNavigationPagesProvider',
            'AgrementAssertion'                          => 'Application\\Assertion\\AgrementAssertion',
        ],
        'initializers' => [
            'Application\Service\Initializer\AgrementServiceAwareInitializer',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'agrementDl'    => 'Application\View\Helper\AgrementDl',
            'isAllowedCRUD' => 'Application\View\Helper\IsAllowedCRUD',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'AgrementSaisieForm' => 'Application\Form\Agrement\Saisie',
        ],
    ],
];
