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
                            ],
                            'defaults'    => [
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'infos'      => [
                                // afficher les messages
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/infos',
                                    'defaults' => [
                                        'action' => 'infos',
                                    ],
                                ],
                            ],
                            'validation' => [
                                // affichage du bouton de validation d'une PJ
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/validation/:typePieceJointe',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'validation',
                                    ],
                                ],
                            ],
                            'valider'    => [
                                // valider la PJ
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/valider/:pieceJointe',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'valider',
                                    ],
                                ],
                            ],
                            'devalider'  => [
                                // dévalider la PJ
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/devalider/:pieceJointe',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'devalider',
                                    ],
                                ],
                            ],
                            'fichier'    => [
                                'type'          => 'Literal',
                                'options'       => [
                                    'route' => '/fichier',
                                ],
                                'may_terminate' => false,
                                'child_routes'  => [
                                    'lister'      => [
                                        // lister les fichiers d'une PJ
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
                                    'televerser'  => [
                                        // télécharger un fichier
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'       => '/televerser/:typePieceJointe[/:fichier/:nomFichier]',
                                            'constraints' => [
                                                'typePieceJointe' => '[0-9]*',
                                                'fichier'         => '[0-9]*',
                                            ],
                                            'defaults'    => [
                                                'action' => 'televerser',
                                            ],
                                        ],
                                    ],
                                    'telecharger' => [
                                        // télécharger un fichier
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'       => '/telecharger/:fichier[/:nomFichier]',
                                            'constraints' => [
                                                'fichier' => '[0-9]*',
                                            ],
                                            'defaults'    => [
                                                'action' => 'telecharger',
                                            ],
                                        ],
                                    ],
                                    'supprimer'   => [
                                        // supprimer un fichier
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'       => '/supprimer/:pieceJointe/:fichier',
                                            'constraints' => [
                                                'pieceJointe' => '[0-9]*',
                                                'fichier'     => '[0-9]*',
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
                    'configuration'                     => [
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
                            'dossier'               => [
                                'label'        => "Données personnelles",
                                'title'        => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'        => 'intervenant/saisir-dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Dossier', 'modifier'),
                            ],
                            'pieces-jointes-saisie' => [
                                'label'        => "Pièces justificatives",
                                'title'        => "Pièces justificatives du dossier de l'intervenant",
                                'route'        => 'piece-jointe/intervenant',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'index'),
                            ],
                            'validation-dossier'    => [
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
                    'gestion'     => [
                        'pages' => [
                            'dossier-pj' => [
                                'border-color' => '#EB4995',
                                'icon'         => 'glyphicon glyphicon-file',
                                'label'        => "Dossier et pièces justificatives",
                                'title'        => "Dossier et pièces justificatives",
                                'route'        => 'piece-jointe/configuration',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'configuration'),
                                'pages'        => [
                                    'type-piece-jointe-statut' => [
                                        'label'      => "Pièces justificatives attendues par statut d'intervenant",
                                        'title'      => "Permet de personnaliser les mièces justificatives à demander en fonction du statut des intervenants",
                                        'route'      => 'piece-jointe/type-piece-jointe-statut',
                                        'withtarget' => true,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'type-piece-jointe-statut'),
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
                /* Dossier */
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['voir'],
                    'privileges' => Privileges::DOSSIER_VISUALISATION,
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['modifier'],
                    'privileges' => Privileges::DOSSIER_EDITION,
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['dossier'],
                    'privileges' => Privileges::DOSSIER_VALIDATION,
                    'assertion'  => 'assertionDossierPieces',
                ],

                /* Interface de configuration des PJ */
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['type-piece-jointe-statut'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['modifier-type-piece-jointe-statut'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['configuration'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                ],

                /* Pièces jointes */
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['index'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['infos', 'lister', 'validation'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['telecharger'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_TELECHARGEMENT,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['televerser', 'supprimer'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_EDITION,
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['valider'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_VALIDATION,
                    'assertion'  => 'assertionDossierPieces',
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['devalider'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION,
                    'assertion'  => 'assertionDossierPieces',
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO => [],
                'PieceJointe'                                    => [],
                Fichier::RESOURCE_ID                             => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::PIECE_JUSTIFICATIVE_VALIDATION,
                            Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION,
                            Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
                            Privileges::PIECE_JUSTIFICATIVE_EDITION,
                        ],
                        'resources'  => ['PieceJointe', 'Intervenant'],
                        'assertion'  => 'assertionDossierPieces',
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
            'applicationTblPieceJointe'        => Service\TblPieceJointeService::class,
            'ApplicationDossier'               => Service\Dossier::class,
            'PossedeDossierRule'               => Rule\Intervenant\PossedeDossierRule::class,
            'ApplicationPieceJointe'           => Service\PieceJointe::class,
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
