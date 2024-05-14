<?php

namespace Signature;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Signature\Controller\SignatureController;
use Signature\Controller\SignatureControllerFactory;

return [
    

    'routes'      => [
        'signatures' => [
            'route'         => '/signature',
            'controller'    => SignatureController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'signature-contrat' => [
                    'route'      => '/signature-contrat',
                    'controller' => SignatureController::class,
                    'action'     => 'signature-contrat',
                ],
            ],
        ],
    ],


    /* Droits d'accès */
    'guards'      => [
        [
            'controller' => SignatureController::class,
            'action'     => ['index', 'signature-contrat'],
            'roles'      => ['guest'],

        ],
    ],

    /* Déclaration du contrôleur */
    'controllers' => [
        SignatureController::class => SignatureControllerFactory::class,
    ],


];