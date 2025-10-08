<?php

namespace PieceJointe;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;
use PieceJointe\Controller\Factory\PieceJointeControllerFactory;
use PieceJointe\Controller\PieceJointeController;
use PieceJointe\Form\Factory\ModifierTypePieceJointeStatutFormFactory;
use PieceJointe\Form\Factory\TypePieceJointeSaisieFormFactory;
use PieceJointe\Form\ModifierTypePieceJointeStatutForm;
use PieceJointe\Form\TypePieceJointeSaisieForm;
use PieceJointe\Service\Factory\PieceJointeServiceFactory;
use PieceJointe\Service\Factory\TblPieceJointeServiceFactory;
use PieceJointe\Service\Factory\TypePieceJointeServiceFactory;
use PieceJointe\Service\Factory\TypePieceJointeStatutServiceFactory;
use PieceJointe\Service\PieceJointeService;
use PieceJointe\Service\TblPieceJointeService;
use PieceJointe\Service\TypePieceJointeService;
use PieceJointe\Service\TypePieceJointeStatutService;
use PieceJointe\Tbl\Process\PieceJointeProcess;
use PieceJointe\Tbl\Process\PieceJointeProcessFactory;

return [
    'routes' => [
        'piece-jointe' => [
            'route'         => '/piece-jointe',
            'controller'    => PieceJointeController::class,
            'may_terminate' => true,
            'child_routes'  => [
                'intervenant' => [
                    'route'         => '/intervenant/:intervenant',
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        'infos'              => [
                            'route'  => '/infos',
                            'action' => 'infos',
                        ],
                        'validation'         => [
                            'route'       => '/validation/:typePieceJointe',
                            'constraints' => [
                                'typePieceJointe' => '[0-9]*',
                            ],
                            'action'      => 'validation',

                        ],
                        'valider'            => [
                            'route'       => '/valider/:pieceJointe',
                            'constraints' => [
                                'pieceJointe' => '[0-9]*',
                                'fichier'     => '[0-9]*',
                            ],
                            'action'      => 'valider',
                        ],
                        'devalider'          => [
                            'route'       => '/devalider/:pieceJointe',
                            'constraints' => [
                                'pieceJointe' => '[0-9]*',
                                'fichier'     => '[0-9]*',
                            ],
                            'action'      => 'devalider',
                        ],
                        'refuser'            => [
                            'route'       => '/refuser/:pieceJointe',
                            'constraints' => [
                                'pieceJointe' => '[0-9]*',
                                'fichier'     => '[0-9]*',
                            ],
                            'action'      => 'refuser',
                        ],
                        'get-pieces-jointes' => [
                            'route'      => '/get-pieces-jointes',
                            'action'     => 'getPiecesJointes',
                        ],
                        'fichier'            => [
                            'route'         => '/fichier',
                            'may_terminate' => false,
                            'child_routes'  => [
                                'lister'      => [
                                    'route'       => '/lister/:typePieceJointe/:pieceJointe',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                        'pieceJointe'     => '[0-9]*',

                                    ],
                                    'action'      => 'lister',

                                ],
                                'televerser'  => [
                                    // télécharger un fichier
                                    'route'       => '/televerser/:typePieceJointe[/:fichier/:nomFichier]',
                                    'constraints' => [
                                        'typePieceJointe' => '[0-9]*',
                                        'fichier'         => '[0-9]*',
                                    ],
                                    'action'      => 'televerser',
                                ],
                                'telecharger' => [
                                    'route'       => '/telecharger/:fichier[/:nomFichier]',
                                    'constraints' => [
                                        'fichier' => '[0-9]*',
                                    ],
                                    'action'      => 'telecharger',
                                ],
                                'supprimer'   => [
                                    'route'       => '/supprimer/:pieceJointe/:fichier',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'action'      => 'supprimer',
                                ],
                                'valider'     => [
                                    'route'       => '/valider/:pieceJointe/:fichier',
                                    'constraints' => [
                                        'pieceJointe' => '[0-9]*',
                                        'fichier'     => '[0-9]*',
                                    ],
                                    'action'      => 'validerFichier',
                                ],
                            ],
                        ],
                    ],
                ],

                'configuration'                     => [
                    'route'  => '/configuration',
                    'action' => 'configuration',
                ],
                'type-piece-jointe-statut'          => [
                    'route'  => '/type-piece-jointe-statut[/:codeTypeIntervenant]',
                    'action' => 'type-piece-jointe-statut',
                ],
                'type-piece-jointe-saisie'          => [
                    'route'         => '/type-piece-jointe-saisie[/:typePieceJointe]',
                    'constraints'   => [
                        'typePieceJointe' => '[0-9]*',
                    ],
                    'action'        => 'type-piece-jointe-saisie',
                    'may_terminate' => true,
                ],
                'type-piece-jointe-delete'          => [
                    'route'         => '/type-piece-jointe-delete[/:typePieceJointe]',
                    'constraints'   => [
                        'typePieceJointe' => '[0-9]*',
                    ],
                    'action'        => 'type-piece-jointe-delete',
                    'may_terminate' => true,
                ],
                'type-piece-jointe-trier'           => [
                    'route'         => '/type-piece-jointe-trier',
                    'action'        => 'type-piece-jointe-trier',
                    'may_terminate' => 'true',
                ],
                'modifier-type-piece-jointe-statut' => [
                    'route'  => '/modifier-type-piece-jointe-statut/:typePieceJointe/:statut[/:typePieceJointeStatut]',
                    'action' => 'modifier-type-piece-jointe-statut',
                ],
                'delete-type-piece-jointe-statut'   => [
                    'route'  => '/delete-type-piece-jointe-statut/:typePieceJointeStatut',
                    'action' => 'delete-type-piece-jointe-statut',
                ],
            ],
        ],
    ],


    'navigation'  => [
        'intervenant' => [
            'pages' => [
                'pieces-jointes-saisie' => [
                    'label'        => "Pièces justificatives",
                    'title'        => "Pièces justificatives du dossier de l'intervenant",
                    'route'        => 'piece-jointe/intervenant',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => Authorize::controllerResource(PieceJointeController::class, 'index'),
                    'order'        => 7,
                ],
            ],
        ],

        'administration' => [
            'pages' => [
                'intervenants' => [
                    'pages' => [
                        'type-piece-jointe-statut' => [
                            'label'      => "Pièces justificatives attendues par statut",
                            'title'      => "Permet de personnaliser les pièces justificatives à demander en fonction du statut des intervenants",
                            'route'      => 'piece-jointe/type-piece-jointe-statut',
                            'withtarget' => true,
                            'order'      => 20,
                            'resource'   => Authorize::controllerResource(PieceJointeController::class, 'type-piece-jointe-statut'),
                        ],
                    ],
                ],
            ],
        ],

    ],
    'guards'      => [

        [
            'controller' => PieceJointeController::class,
            'action'     => ['type-piece-jointe-statut'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['modifier-type-piece-jointe-statut', 'delete-type-piece-jointe-statut'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['configuration'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['type-piece-jointe-delete'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['type-piece-jointe-saisie'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['type-piece-jointe-trier'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_GESTION_EDITION,
        ],
        /* Pièces jointes */
        [
            'controller' => PieceJointeController::class,
            'action'     => ['index', 'indexNew', 'getPiecesJointes'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
            'assertion'  => Assertion\PiecesJointesAssertion::class,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['infos', 'lister', 'validation', 'refuser'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['telecharger'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_TELECHARGEMENT,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['televerser', 'supprimer'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_EDITION,
            'assertion'  => Assertion\PiecesJointesAssertion::class,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['archiver'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_ARCHIVAGE,
            'assertion'  => Assertion\PiecesJointesAssertion::class,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['valider', 'validerFichier'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_VALIDATION,
            'assertion'  => Assertion\PiecesJointesAssertion::class,
        ],
        [
            'controller' => PieceJointeController::class,
            'action'     => ['devalider'],
            'privileges' => Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION,
            'assertion'  => Assertion\PiecesJointesAssertion::class,
        ],
    ],
    'rules'       => [
        [
            'privileges' => [
                Privileges::PIECE_JUSTIFICATIVE_VALIDATION,
                Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION,
                Privileges::PIECE_JUSTIFICATIVE_VISUALISATION,
                Privileges::PIECE_JUSTIFICATIVE_EDITION,


            ],
            'resources'  => ['PieceJointe', 'Intervenant'],
            'assertion'  => Assertion\PiecesJointesAssertion::class,
        ],


    ],
    'controllers' => [
        PieceJointeController::class => PieceJointeControllerFactory::class,
    ],
    'services'    => [
        TblPieceJointeService::class        => TblPieceJointeServiceFactory::class,
        PieceJointeService::class           => PieceJointeServiceFactory::class,
        TypePieceJointeService::class       => TypePieceJointeServiceFactory::class,
        TypePieceJointeStatutService::class => TypePieceJointeStatutServiceFactory::class,
        PieceJointeProcess::class           => PieceJointeProcessFactory::class,

    ],

    'forms' => [
        ModifierTypePieceJointeStatutForm::class => ModifierTypePieceJointeStatutFormFactory::class,
        TypePieceJointeSaisieForm::class         => TypePieceJointeSaisieFormFactory::class,
    ],
];
