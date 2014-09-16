<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Controller\AgrementController;

return array(
    'router' => array(
        'routes' => array(
            'intervenant' => array(
                'child_routes' => array(
                    'agrement' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/agrement',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Agrement',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'liste' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/:typeAgrement',
                                    'constraints' => array(
                                        'typeAgrement' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'lister',
                                    ),
                                ),
                            ),
                            'ajouter' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/:typeAgrement/ajouter',
                                    'constraints' => array(
                                        'typeAgrement' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => AgrementController::ACTION_AJOUTER,
                                    ),
                                ),
                            ),
                            'voir' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/voir/:agrement',
                                    'constraints' => array(
                                        'agrement' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => AgrementController::ACTION_VOIR,
                                    ),
                                ),
                            ),
                            'voir-str' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/:typeAgrement/voir-str[/:structure]',
                                    'constraints' => array(
                                        'typeAgrement' => '[0-9]*',
                                        'structure' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => AgrementController::ACTION_VOIR_STR,
                                    ),
                                ),
                            ),
                            'modifier' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/modifier/:agrement',
                                    'constraints' => array(
                                        'agrement' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => AgrementController::ACTION_MODIFIER,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'gestion' => array(
                'child_routes' => array(
                    'agrement' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route' => '/agrement',
                            'defaults' => array(
                                'controller' => 'Agrement',
                                'action' => 'index'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'ajouter-lot' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/:typeAgrement/ajouter-lot',
                                    'constraints' => array(
                                        'typeAgrement' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => AgrementController::ACTION_AJOUTER_LOT,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'intervenant' => array(
                        'pages' => array(
                            'agrement' => array(
                                'label'         => "Agrément",
                                'title'         => "Agrément de l'intervenant",
                                'route'         => 'intervenant/agrement',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget'    => true,
                                'resource'      => 'controller/Application\Controller\Agrement:index',
                                'visible'       => 'IntervenantNavigationPageVisibility',
                                'pagesProvider' => array(
                                    'type'       => 'AgrementIntervenantNavigationPagesProvider',
                                    'route'      => 'intervenant/agrement/liste',
                                    'paramsInject' => array(
                                        'intervenant',
                                    ),
                                    'withtarget' => true,
                                    'resource'   => 'controller/Application\Controller\Agrement:lister',
                                    'visible'    => 'IntervenantNavigationPageVisibility',
                                ),
                            ),
                        ),
                    ),
                    'gestion' => array(
                        'pages' => array(
                            'agrement' => array(
                                'label'  => "Agréments par lot",
                                'title'  => "Gestion des agréments par lot",
                                'route'  => 'gestion/agrement',
                                'resource' => 'controller/Application\Controller\Agrement:index',
                                'pagesProvider' => array(
                                    'type'  => 'AgrementNavigationPagesProvider',
                                    'route' => 'gestion/agrement/ajouter-lot',
                                    'withtarget' => true,
                                    'resource'   => 'controller/Application\Controller\Agrement:' . AgrementController::ACTION_AJOUTER_LOT,
                                    'privilege'  => AgrementController::ACTION_AJOUTER_LOT,
                                    // NB: le code du type d'agrément sera concaténé au 'privilege' par le AgrementNavigationPagesProvider
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => array('index', 'lister', 'voir'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, 'Administrateur'),
                    'assertion'  => 'AgrementAssertion',
                ),
                array(
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => array('ajouter', 'ajouter-lot', 'modifier', 'supprimer', 'voir-str'),
                    'roles'      => array(ComposanteRole::ROLE_ID, 'Administrateur'),
                    'assertion'  => 'AgrementAssertion',
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Agrement' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(
                        array(ComposanteRole::ROLE_ID, 'Administrateur'), 
                        'Agrement', 
                        array('create', 'read', 'delete', 'update'), 
                        'AgrementAssertion',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Agrement' => 'Application\Controller\AgrementController',
        ),
        'initializers' => array(
            'Application\Service\Initializer\AgrementServiceAwareInitializer',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationAgrement'                        => 'Application\\Service\\Agrement',
            'ApplicationTypeAgrement'                    => 'Application\\Service\\TypeAgrement',
            'ApplicationTypeAgrementStatut'              => 'Application\\Service\\TypeAgrementStatut',
            'AgrementNavigationPagesProvider'            => 'Application\\Service\\AgrementNavigationPagesProvider',
            'AgrementIntervenantNavigationPagesProvider' => 'Application\\Service\\AgrementIntervenantNavigationPagesProvider',
            'AgrementAssertion'                          => 'Application\\Service\\Assertion\\AgrementAssertion',
        ),
        'initializers' => array(
            'Application\Service\Initializer\AgrementServiceAwareInitializer',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'agrementDl'    => 'Application\View\Helper\AgrementDl',
            'isAllowedCRUD' => 'Application\View\Helper\IsAllowedCRUD',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'AgrementSaisieForm' => 'Application\Form\Agrement\Saisie',
        ),
    ),
);
