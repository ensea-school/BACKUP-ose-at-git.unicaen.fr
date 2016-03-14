<?php

namespace Application;

use Application\Assertion\OldAbstractAssertion;
use Application\Entity\Db\PieceJointe;
use Application\Assertion\PieceJointeAssertion;
use Application\Entity\Db\Fichier;
use Application\Assertion\FichierAssertion;
use Application\Acl\ComposanteRole;
use Application\Acl\AdministrateurRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'intervenant'  => [
                'child_routes' => [
                    'saisir-dossier'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/saisir-dossier',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'Dossier',
                                'action'     => 'modifier',
                            ],
                        ],
                    ],
                    'validation-dossier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/validation/dossier',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => 'Validation',
                                'action'     => 'dossier',
                            ],
                        ],
                    ],
                ],
            ],
            'piece-jointe' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/piece-jointe',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'PieceJointe',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'intervenant'                       => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/intervenant/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                                'type'        => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            // une route 'validation' dédiée à l'étape du WF est indispensable
                            'validation'  => [
                                'type'     => 'Segment',
                                'options'  => [
                                    'route' => '/validation',
                                ],
                                'defaults' => [
                                    'action' => 'index',
                                ],
                            ],
                            'voir'        => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/voir/:pieceJointe/vue/:vue',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'voir',
                                    ],
                                ],
                            ],
                            'voir-type'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/voir-type/:typePieceJointe/vue/:vue',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'voir-type',
                                    ],
                                ],
                            ],
                            'lister'      => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/lister/:typePieceJointe',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'lister',
                                    ],
                                ],
                            ],
                            'status'      => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/status',
                                    'defaults' => [
                                        'action' => 'status',
                                    ],
                                ],
                            ],
                            'ajouter'     => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/ajouter/:typePieceJointe',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'supprimer'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/supprimer/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'telecharger' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/telecharger/:pieceJointe[/fichier/:fichier/:nomFichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'telecharger',
                                    ],
                                ],
                            ],
                            'valider'     => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/valider/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'valider',
                                    ],
                                ],
                            ],
                            'devalider'   => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/devalider/:pieceJointe[/fichier/:fichier]',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'devalider',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'configuration' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/configuration',
                            'defaults' => [
                                'action' => 'configuration',
                            ],
                        ],
                    ],
                    'type-piece-jointe-statut'          => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/type-piece-jointe-statut',
                            'defaults' => [
                                'action' => 'type-piece-jointe-statut',
                            ],
                        ],
                    ],
                    'modifier-type-piece-jointe-statut' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier-type-piece-jointe-statut/:typePieceJointe/:statutIntervenant/:premierRecrutement',
                            'defaults' => [
                                'action' => 'modifier-type-piece-jointe-statut',
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
                            'dossier'                   => [
                                'label'        => "Données personnelles",
                                'title'        => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'        => 'intervenant/saisir-dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Dossier', 'modifier'),
                            ],
                            'pieces-jointes-saisie'     => [
                                'label'        => "Pièces justificatives",
                                'title'        => "Pièces justificatives du dossier de l'intervenant",
                                'route'        => 'piece-jointe/intervenant',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'index'),
                            ],
                            'validation-dossier'        => [
                                'label'        => "Validation des données personnelles",
                                'title'        => "Validation des données personnelles de l'intervenant",
                                'route'        => 'intervenant/validation-dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Validation', 'dossier'),
                            ],
                        ],
                    ],
                    'gestion' => [
                        'pages' => [
                            'dossier-pj'     => [
                                'border-color' => '#EB4995',
                                'icon'         => 'glyphicon glyphicon-file',
                                'label'        => "Dossier et pièces justificatives",
                                'title'        => "Dossier et pièces justificatives",
                                'route'        => 'piece-jointe/configuration',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'configuration'),
                                'pages' => [
                                    'type-piece-jointe-statut'     => [
                                        'label'        => "Pièces justificatives attendues par statut d'intervenant",
                                        'title'        => "Permet de personnaliser les mièces justificatives à demander en fonction du statut des intervenants",
                                        'route'        => 'piece-jointe/type-piece-jointe-statut',
                                        'withtarget'   => true,
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'type-piece-jointe-statut'),
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
            PrivilegeController::class      => [
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['voir', 'modifier'],
                    'privileges' => [Privileges::DOSSIER_VISUALISATION, Privileges::DOSSIER_EDITION],
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['dossier'],
                    'privileges' => [Privileges::DOSSIER_VALIDATION],
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['type-piece-jointe-statut'],
                    'privileges' => [
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['modifier-type-piece-jointe-statut'],
                    'privileges' => [
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['configuration'],
                    'privileges' => [
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                    ],
                ],
            ],
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
                Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO => [],
                PieceJointe::RESOURCE_ID => [],
                Fichier::RESOURCE_ID     => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [

            ],
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        [
                            R_INTERVENANT,
                            R_COMPOSANTE,
                            R_ADMINISTRATEUR,
                        ],
                        [
                            Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO,
                        ],
                        [
                            OldAbstractAssertion::PRIVILEGE_READ,
                        ],
                        'ValidationAssertion',
                    ],
                    // ------------- Validation DONNEES PERSO -------------
                    [
                        [
                            R_COMPOSANTE,
                            R_ADMINISTRATEUR,
                        ],
                        [
                            Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO,
                        ],
                        [
                            OldAbstractAssertion::PRIVILEGE_CREATE,
                            OldAbstractAssertion::PRIVILEGE_DELETE,
                        ],
                        'ValidationAssertion',
                    ],

                    [
                        [
                            R_INTERVENANT_EXTERIEUR,
                            R_COMPOSANTE,
                            R_ADMINISTRATEUR,
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
                            R_COMPOSANTE,
                            R_ADMINISTRATEUR,
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
                            R_INTERVENANT_EXTERIEUR,
                            R_COMPOSANTE,
                            R_ADMINISTRATEUR,
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
                            R_COMPOSANTE,
                            R_ADMINISTRATEUR,
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
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Dossier'     => Controller\DossierController::class,
            'Application\Controller\PieceJointe' => Controller\PieceJointeController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationDossier'    => Service\Dossier::class,
            'PossedeDossierRule'    => Rule\Intervenant\PossedeDossierRule::class,
            'PeutSaisirDossierRule' => Rule\Intervenant\PeutSaisirDossierRule::class,

            'ApplicationPieceJointe'           => Service\PieceJointe::class,
            'ApplicationPieceJointeProcess'    => Service\Process\PieceJointeProcess::class,
            'ApplicationTypePieceJointe'       => Service\TypePieceJointe::class,
            'ApplicationTypePieceJointeStatut' => Service\TypePieceJointeStatut::class,
            'PeutSaisirPieceJointeRule'        => Rule\Intervenant\PeutSaisirPieceJointeRule::class,
            'PieceJointeAssertion'             => Assertion\PieceJointeAssertion::class,
            'FichierAssertion'                 => Assertion\FichierAssertion::class,
            'assertionDossierPieces'           => Assertion\DossierPiecesAssertion::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'validation' => View\Helper\ValidationViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'IntervenantDossier' => Form\Intervenant\Dossier::class,
        ],
    ],
];
