<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'intervenant'  => [
                'child_routes' => [
                    'dossier' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/:intervenant/dossier',
                            'defaults'    => [
                                'controller' => 'Application\Controller\Dossier',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'valider'            => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/valider',
                                    'defaults' => [
                                        'action' => 'valider',
                                    ],
                                ],
                            ],
                            'devalider'          => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/devalider',
                                    'defaults' => [
                                        'action' => 'devalider',
                                    ],
                                ],
                            ],
                            'supprimer'          => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/supprimer',
                                    'defaults' => [
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'differences'        => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/differences',
                                    'defaults' => [
                                        'action' => 'differences',
                                    ],
                                ],
                            ],
                            'purger-differences' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/purger-differences',
                                    'defaults' => [
                                        'action' => 'purger-differences',
                                    ],
                                ],
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
                        'controller'    => 'Application\Controller\PieceJointe',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'intervenant'                       => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/intervenant/:intervenant',
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
                    'type-piece-jointe-saisie'          => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-piece-jointe-saisie[/:typePieceJointe]',
                            'constraints' => [
                                'typePieceJointe' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-piece-jointe-saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-piece-jointe-delete'          => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-piece-jointe-delete[/:typePieceJointe]',
                            'constraints' => [
                                'typePieceJointe' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-piece-jointe-delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-piece-jointe-trier'           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'      => '/type-piece-jointe-trier',
                            'contraints' => [
                            ],
                            'defaults'   => [
                                'action' => 'type-piece-jointe-trier',
                            ],
                        ],
                        'may_terminate' => 'true',
                    ],
                    'modifier-type-piece-jointe-statut' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier-type-piece-jointe-statut/:typePieceJointe/:statutIntervenant[/:typePieceJointeStatut]',
                            'defaults' => [
                                'action' => 'modifier-type-piece-jointe-statut',
                            ],
                        ],
                    ],
                    'delete-type-piece-jointe-statut'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/delete-type-piece-jointe-statut/:typePieceJointeStatut',
                            'defaults' => [
                                'action' => 'delete-type-piece-jointe-statut',
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
                    'intervenant'    => [
                        'pages' => [
                            'dossier'               => [
                                'label'        => "Données personnelles",
                                'title'        => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'        => 'intervenant/dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Dossier', 'index'),
                                'order'        => 5,
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
                                'order'        => 7,
                            ],
                        ],
                    ],
                    'administration' => [
                        'pages' => [
                            'dossier-pj' => [
                                'icon'         => 'glyphicon glyphicon-file',
                                'label'        => "Dossiers et pièces justificatives",
                                'route'        => 'piece-jointe/configuration',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'configuration'),
                                'order'        => 90,
                                'border-color' => '#A22CAE',
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
                    'action'     => ['index'],
                    'privileges' => [Privileges::DOSSIER_VISUALISATION],
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['differences'],
                    'privileges' => [Privileges::DOSSIER_DIFFERENCES],
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['purger-differences'],
                    'privileges' => [Privileges::DOSSIER_PURGER_DIFFERENCES],
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['valider'],
                    'privileges' => [Privileges::DOSSIER_VALIDATION],
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['devalider'],
                    'privileges' => [Privileges::DOSSIER_DEVALIDATION],
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['supprimer'],
                    'privileges' => [Privileges::DOSSIER_SUPPRESSION],
                ],


                /* Interface de configuration des PJ */
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['type-piece-jointe-statut'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['modifier-type-piece-jointe-statut', 'delete-type-piece-jointe-statut'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['configuration'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['type-piece-jointe-delete'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['type-piece-jointe-saisie'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['type-piece-jointe-trier'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
                ],
                /* Pièces jointes */
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['index'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
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
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['valider'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_VALIDATION,
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\PieceJointe',
                    'action'     => ['devalider'],
                    'privileges' => Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION,
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'PieceJointe' => [],
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
                        'assertion'  => Assertion\DossierPiecesAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories'  => [
            'Application\Controller\Dossier' => Controller\Factory\DossierControllerFactory::class,
        ],
        'invokables' => [
            'Application\Controller\PieceJointe' => Controller\PieceJointeController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\TblPieceJointeService::class        => Service\TblPieceJointeService::class,
            Service\DossierService::class               => Service\DossierService::class,
            Service\PieceJointeService::class           => Service\PieceJointeService::class,
            Service\TypePieceJointeService::class       => Service\TypePieceJointeService::class,
            Service\TypePieceJointeStatutService::class => Service\TypePieceJointeStatutService::class,
        ],
        'factories' => [
            Assertion\DossierPiecesAssertion::class     => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'validation' => View\Helper\ValidationViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Intervenant\Dossier::class                           => Form\Intervenant\Dossier::class,
            Form\PieceJointe\TypePieceJointeSaisieForm::class         => Form\PieceJointe\TypePieceJointeSaisieForm::class,
            Form\PieceJointe\ModifierTypePieceJointeStatutForm::class => Form\PieceJointe\ModifierTypePieceJointeStatutForm::class,
        ],
    ],
];
