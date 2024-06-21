<?php

namespace Signature;

use PieceJointe\Controller\PieceJointeController;
use Signature\Controller\CircuitController;
use Signature\Controller\CircuitControllerFactory;
use Signature\Controller\SignatureController;
use Signature\Controller\SignatureControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [


    'routes' => [
        'signature' => [
            'route'         => '/signature',
            'controller'    => SignatureController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'configuration'    => [
                    'route'      => '/configuration',
                    'controller' => SignatureController::class,
                    'action'     => 'configuration',
                ],
                'signature-simple' => [
                    'route'      => '/signature-simple',
                    'controller' => SignatureController::class,
                    'action'     => 'signature-simple',
                ],
                'circuits'         => [
                    'route'         => '/circuits',
                    'controller'    => CircuitController::class,
                    'action'        => 'circuits',
                    'may_terminate' => true,
                    'child_routes'  => [
                        
                    ],
                ],


            ],
        ],
    ],

    'navigation'  => [
        'administration' => [
            'pages' => [
                'signature' => [
                    'pages' => [
                        'signature-configuration' => [
                            'label'      => "Liste des parapheurs",
                            'title'      => "Permet de visualiser la liste et la configuration des différents parapheurs.",
                            'route'      => 'signature/configuration',
                            'withtarget' => true,
                            'order'      => 20,
                            //'resource'   => PrivilegeController::getResourceId(PieceJointeController::class, 'type-piece-jointe-statut'),
                        ],
                        'signature-simple'        => [
                            'label'      => "Faire signer un document",
                            'title'      => "Permet d'envoyer un document à signer dans Esup",
                            'route'      => 'signature/signature-simple',
                            'withtarget' => true,
                            'order'      => 20,
                            //'resource'   => PrivilegeController::getResourceId(PieceJointeController::class, 'type-piece-jointe-statut'),

                        ],
                        'gestion-circuit'         => [
                            'label'      => "Gestion des circuits de signature",
                            'title'      => "Gestion des circuits de signature",
                            'route'      => 'signature/circuits',
                            'withtarget' => true,
                            'order'      => 20,
                            //'resource'   => PrivilegeController::getResourceId(PieceJointeController::class, 'type-piece-jointe-statut'),

                        ],
                    ],
                ],
            ],
        ],
    ],
    /* Droits d'accès */
    'guards'      => [
        [
            'controller' => SignatureController::class,
            'action'     => ['index', 'configuration', 'signature-simple'],
            'roles'      => ['guest'],

        ],
        [
            'controller' => CircuitController::class,
            'action'     => ['index', 'circuits'],
            'roles'      => ['guest'],

        ],
    ],

    /* Déclaration du contrôleur */
    'controllers' => [
        SignatureController::class => SignatureControllerFactory::class,
        CircuitController::class   => CircuitControllerFactory::class,
    ],

    'services' => [
        Service\CircuitService::class => Service\CircuitServiceFactory::class,
    ],


];