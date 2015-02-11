<?php

namespace Application;

use Application\Entity\Db\PieceJointe;
use Application\Assertion\PieceJointeAssertion;
use Application\Entity\Db\Fichier;
use Application\Assertion\FichierAssertion;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
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
                            // une route 'validation' dédiée à l'étape du WF est indispensable 
                            'validation' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/validation',
                                ),
                                'defaults' => array(
                                    'action' => 'index',
                                ),
                            ),
                            'voir' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/voir/:pieceJointe/vue/:vue',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'voir',
                                    ),
                                ),
                            ),
                            'voir-type' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/voir-type/:typePieceJointe/vue/:vue',
                                    'constraints' => array(
                                        'typePieceJointe' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'voir-type',
                                    ),
                                ),
                            ),
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
                            'status' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/status',
                                    'defaults' => array(
                                        'action' => 'status',
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
                                    'route' => '/supprimer/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'supprimer',
                                    ),
                                ),
                            ),
                            'telecharger' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/telecharger/:pieceJointe[/fichier/:fichier/:nomFichier]',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'telecharger',
                                    ),
                                ),
                            ),
                            'valider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/valider/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action' => 'valider',
                                    ),
                                ),
                            ),
                            'devalider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/devalider/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => array(
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
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
                            'pieces-jointes-saisie' => array(
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
                            'pieces-jointes-validation' => array(
                                'label'  => "Validation des pièces justificatives",
                                'title'  => "Validation des pièces justificatives du dossier de l'intervenant",
                                'route'  => 'piece-jointe/intervenant/validation',
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
                    'action'     => array('index', 'ajouter', 'supprimer', 'voir', 'voir-type', 'lister', 'telecharger', 'status'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID),
                    'assertion'  => 'PieceJointeAssertion',
                ),
                array(
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => array('valider', 'devalider'),
                    'roles'      => array(ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID),
                    'assertion'  => 'PieceJointeAssertion',
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                PieceJointe::RESOURCE_ID => array(),
                Fichier::RESOURCE_ID => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    /**
                     * Pièces jointes
                     */
                    array(
                        array(
                            IntervenantExterieurRole::ROLE_ID, 
                            ComposanteRole::ROLE_ID, 
                            AdministrateurRole::ROLE_ID,
                        ), 
                        PieceJointe::RESOURCE_ID, 
                        array(
                            PieceJointeAssertion::PRIVILEGE_CREATE, 
                            PieceJointeAssertion::PRIVILEGE_READ, 
                            PieceJointeAssertion::PRIVILEGE_DELETE, 
                            PieceJointeAssertion::PRIVILEGE_CREATE_FICHIER, 
                        ), 
                        'PieceJointeAssertion',
                    ),
                    array(
                        array(
                            ComposanteRole::ROLE_ID, 
                            AdministrateurRole::ROLE_ID,
                        ), 
                        PieceJointe::RESOURCE_ID, 
                        array(
                            PieceJointeAssertion::PRIVILEGE_VALIDER, 
                            PieceJointeAssertion::PRIVILEGE_DEVALIDER, 
                        ), 
                        'PieceJointeAssertion',
                    ),
                    /**
                     * Fichiers déposés
                     */
                    array(
                        array(
                            IntervenantExterieurRole::ROLE_ID, 
                            ComposanteRole::ROLE_ID, 
                            AdministrateurRole::ROLE_ID,
                        ), 
                        Fichier::RESOURCE_ID, 
                        array(
                            FichierAssertion::PRIVILEGE_CREATE, 
                            FichierAssertion::PRIVILEGE_READ, 
                            FichierAssertion::PRIVILEGE_DELETE,
                            FichierAssertion::PRIVILEGE_TELECHARGER, 
                        ), 
                        'FichierAssertion',
                    ),
                    array(
                        array(
                            ComposanteRole::ROLE_ID, 
                            AdministrateurRole::ROLE_ID,
                        ), 
                        Fichier::RESOURCE_ID, 
                        array(
                            FichierAssertion::PRIVILEGE_VALIDER, 
                            FichierAssertion::PRIVILEGE_DEVALIDER, 
                        ), 
                        'FichierAssertion',
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
            'PeutSaisirPieceJointeRule'        => 'Application\\Rule\\Intervenant\\PeutSaisirPieceJointeRule',
            'PiecesJointesFourniesRule'        => 'Application\\Rule\\Intervenant\\PiecesJointesFourniesRule',
            'PieceJointeAssertion'             => 'Application\\Assertion\\PieceJointeAssertion',
            'FichierAssertion'                 => 'Application\\Assertion\\FichierAssertion',
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
