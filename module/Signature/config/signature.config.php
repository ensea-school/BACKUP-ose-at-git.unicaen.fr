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
                'liste-contrat'    => [
                    'route'      => '/liste-contrat',
                    'controller' => SignatureController::class,
                    'action'     => 'liste-contrat',
                ],
                'get-data-contrat' => [
                    'route'         => '/data-contrat',
                    'controller'    => SignatureController::class,
                    'action'        => 'get-data-contrat',
                    'may_terminate' => true,
                ],
                'get-document'     => [
                    'route'         => '/:signature/get-document',
                    'controller'    => SignatureController::class,
                    'action'        => 'get-document',
                    'may_terminate' => true,
                ],
                'update-signature' => [
                    'route'         => '/:signature/update-signature',
                    'controller'    => SignatureController::class,
                    'action'        => 'update-signature',
                    'may_terminate' => true,
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
                        'signature-contrat-liste' => [
                            'label'      => "Liste des signatures de contrat",
                            'title'      => "Liste les signatures électroniques de contrat",
                            'route'      => 'signature/liste-contrat',
                            'withtarget' => true,
                            'order'      => 30,
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
            'action'     => ['index', 'configuration', 'liste-contrat', 'get-data-contrat', 'get-document', 'update-signature'],
            'roles'      => ['guest'],

        ],

    ],

    /* Déclaration du contrôleur */
    'controllers' => [
        SignatureController::class => SignatureControllerFactory::class,

    ],

    'services' => [

    ],


];