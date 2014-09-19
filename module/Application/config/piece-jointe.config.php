<?php

namespace Application;

use Application\Entity\Db\PieceJointe;
use Application\Assertion\PieceJointeAssertion;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantExterieurRole;

return array(
    'router' => array(
        'routes' => array(
            'piece-jointe' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/piece-jointe',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'PieceJointe',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'intervenant' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/intervenant/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                                'type' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'lister' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/lister/:typePieceJointe',
                                    'constraints' => array(
                                        'typePieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'lister',
                                    ),
                                ),
                            ),
                            'ajouter' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/ajouter/:typePieceJointe',
                                    'constraints' => array(
                                        'typePieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'ajouter',
                                    ),
                                ),
                            ),
                            'supprimer' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/supprimer/:pieceJointe/:nomFichier',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'supprimer',
                                    ),
                                ),
                            ),
                            'telecharger' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/telecharger/:pieceJointe/:nomFichier',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'telecharger',
                                    ),
                                ),
                            ),
                            'valider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/valider/:pieceJointe',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'valider',
                                    ),
                                ),
                            ),
                            'devalider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/devalider/:pieceJointe',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'devalider',
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
                            'pieces-jointes' => array(
                                'label'  => "Pièces justificatives",
                                'title'  => "Pièces justificatives du dossier de l'intervenant",
                                'route'  => 'piece-jointe/intervenant',
                                'paramsInject' => array(
                                    'intervenant',
                                ),
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\PieceJointe:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
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
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => array('index', 'ajouter', 'supprimer', 'lister', 'telecharger'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID,'Administrateur'),
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                PieceJointe::RESOURCE_ID => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(
                        array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, 'Administrateur'), 
                        PieceJointe::RESOURCE_ID, 
                        array(
                            PieceJointeAssertion::PRIVILEGE_CREATE, 
                            PieceJointeAssertion::PRIVILEGE_READ, 
                            PieceJointeAssertion::PRIVILEGE_DELETE, 
                            PieceJointeAssertion::PRIVILEGE_UPDATE, 
                            PieceJointeAssertion::PRIVILEGE_TELECHARGER, 
                            PieceJointeAssertion::PRIVILEGE_VALIDER, 
                            PieceJointeAssertion::PRIVILEGE_DEVALIDER), 
                        'PieceJointeAssertion',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\PieceJointe' => 'Application\Controller\PieceJointeController',
        ),
        'initializers' => array(
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationPieceJointe'           => 'Application\\Service\\PieceJointe',
            'ApplicationPieceJointeProcess'    => 'Application\\Service\\Process\PieceJointeProcess',
            'ApplicationTypePieceJointe'       => 'Application\\Service\\TypePieceJointe',
            'ApplicationTypePieceJointeStatut' => 'Application\\Service\\TypePieceJointeStatut',
            'PiecesJointesFourniesRule'        => 'Application\\Rule\\Intervenant\\PiecesJointesFourniesRule',
            'PieceJointeAssertion'             => 'Application\\Assertion\\AgrementAssertion',
        ),
        'initializers' => array(
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
        'initializers' => array(
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
        ),
    ),
);
