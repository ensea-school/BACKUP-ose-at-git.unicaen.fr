<?php

namespace Signature;

use Application\Provider\Privilege\Privileges;
use Mission\Controller\OffreEmploiController;
use Mission\Controller\OffreEmploiControllerFactory;
use Signature\Controller\SignatureControllerFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenSignature\Controller\SignatureController;

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