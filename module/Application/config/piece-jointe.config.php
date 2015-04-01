<?php

namespace Application;

use Application\Entity\Db\PieceJointe;
use Application\Assertion\PieceJointeAssertion;
use Application\Entity\Db\Fichier;
use Application\Assertion\FichierAssertion;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Acl\IntervenantExterieurRole;

return [
    'router' => [
        'routes' => [
            'piece-jointe' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/piece-jointe',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'PieceJointe',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'intervenant' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/intervenant/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                                'type' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            // une route 'validation' dédiée à l'étape du WF est indispensable
                            'validation' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/validation',
                                ],
                                'defaults' => [
                                    'action' => 'index',
                                ],
                            ],
                            'voir' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/voir/:pieceJointe/vue/:vue',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'voir',
                                    ],
                                ],
                            ],
                            'voir-type' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/voir-type/:typePieceJointe/vue/:vue',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'voir-type',
                                    ],
                                ],
                            ],
                            'lister' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/lister/:typePieceJointe',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'lister',
                                    ],
                                ],
                            ],
                            'status' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/status',
                                    'defaults' => [
                                        'action' => 'status',
                                    ],
                                ],
                            ],
                            'ajouter' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/ajouter/:typePieceJointe',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/supprimer/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'telecharger' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/telecharger/:pieceJointe[/fichier/:fichier/:nomFichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'telecharger',
                                    ],
                                ],
                            ],
                            'valider' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/valider/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'valider',
                                    ],
                                ],
                            ],
                            'devalider' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/devalider/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'devalider',
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
                            'pieces-jointes-saisie' => [
                                'label'  => "Pièces justificatives",
                                'title'  => "Pièces justificatives du dossier de l'intervenant",
                                'route'  => 'piece-jointe/intervenant',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\PieceJointe:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
                            ],
                            'pieces-jointes-validation' => [
                                'label'  => "Validation des pièces justificatives",
                                'title'  => "Validation des pièces justificatives du dossier de l'intervenant",
                                'route'  => 'piece-jointe/intervenant/validation',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\PieceJointe:index',
                                'visible' => 'IntervenantNavigationPageVisibility',
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
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['index', 'ajouter', 'supprimer', 'voir', 'voir-type', 'lister', 'telecharger', 'status'],
                    'roles'      => [IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                    'assertion'  => 'PieceJointeAssertion',
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['valider', 'devalider'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                    'assertion'  => 'PieceJointeAssertion',
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                PieceJointe::RESOURCE_ID => [],
                Fichier::RESOURCE_ID => [],
            ],
        ],
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    /**
                     * Pièces jointes
                     */
                    [
                        [
                            IntervenantExterieurRole::ROLE_ID,
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        PieceJointe::RESOURCE_ID,
                        [
                            PieceJointeAssertion::PRIVILEGE_CREATE,
                            PieceJointeAssertion::PRIVILEGE_READ,
                            PieceJointeAssertion::PRIVILEGE_DELETE,
                            PieceJointeAssertion::PRIVILEGE_CREATE_FICHIER,
                        ],
                        'PieceJointeAssertion',
                    ],
                    [
                        [
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        PieceJointe::RESOURCE_ID,
                        [
                            PieceJointeAssertion::PRIVILEGE_VALIDER,
                            PieceJointeAssertion::PRIVILEGE_DEVALIDER,
                        ],
                        'PieceJointeAssertion',
                    ],
                    /**
                     * Fichiers déposés
                     */
                    [
                        [
                            IntervenantExterieurRole::ROLE_ID,
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        Fichier::RESOURCE_ID,
                        [
                            FichierAssertion::PRIVILEGE_CREATE,
                            FichierAssertion::PRIVILEGE_READ,
                            FichierAssertion::PRIVILEGE_DELETE,
                            FichierAssertion::PRIVILEGE_TELECHARGER,
                        ],
                        'FichierAssertion',
                    ],
                    [
                        [
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        Fichier::RESOURCE_ID,
                        [
                            FichierAssertion::PRIVILEGE_VALIDER,
                            FichierAssertion::PRIVILEGE_DEVALIDER,
                        ],
                        'FichierAssertion',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\PieceJointe' => 'Application\Controller\PieceJointeController',
        ],
        'initializers' => [
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationPieceJointe'           => 'Application\\Service\\PieceJointe',
            'ApplicationPieceJointeProcess'    => 'Application\\Service\\Process\PieceJointeProcess',
            'ApplicationTypePieceJointe'       => 'Application\\Service\\TypePieceJointe',
            'ApplicationTypePieceJointeStatut' => 'Application\\Service\\TypePieceJointeStatut',
            'PeutSaisirPieceJointeRule'        => 'Application\\Rule\\Intervenant\\PeutSaisirPieceJointeRule',
            'PiecesJointesFourniesRule'        => 'Application\\Rule\\Intervenant\\PiecesJointesFourniesRule',
            'PieceJointeAssertion'             => 'Application\\Assertion\\PieceJointeAssertion',
            'FichierAssertion'                 => 'Application\\Assertion\\FichierAssertion',
        ],
        'initializers' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
        'initializers' => [
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];
