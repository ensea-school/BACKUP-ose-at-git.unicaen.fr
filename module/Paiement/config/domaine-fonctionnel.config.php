<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'domaine-fonctionnel' => [
            'route'         => '/domaine-fonctionnel',
            'controller'    => Controller\DomaineFonctionnelController::class,
            'action'        => 'index',
            'privileges'    => Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'delete' => [
                    'route'       => '/delete/:domaineFonctionnel',
                    'constraints' => [
                        'domaineFonctionnel' => '[0-9]*',
                    ],
                    'controller'  => Controller\DomaineFonctionnelController::class,
                    'action'      => 'delete',
                    'privileges'  => Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_EDITION,
                ],
                'saisie' => [
                    'route'       => '/saisie/[:domaineFonctionnel]',
                    'constraints' => [
                        'domaineFonctionnel' => '[0-9]*',
                    ],
                    'controller'  => Controller\DomaineFonctionnelController::class,
                    'action'      => 'saisie',
                    'privileges'  => Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_EDITION,
                ],
            ],
        ],
    ],


    'navigation' => [
        'administration' => [
            'pages' => [
                'finances' => [
                    'pages' => [
                        'domaine-fonctionnel' => [
                            'label'    => 'Domaines fonctionnels',
                            'route'    => 'domaine-fonctionnel',
                            'resource' => Authorize::controllerResource(Controller\DomaineFonctionnelController::class, 'index'),
                            'order'    => 20,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],


    'controllers' => [
        Controller\DomaineFonctionnelController::class => Controller\DomaineFonctionnelControllerFactory::class,
    ],


    'services' => [
        Service\DomaineFonctionnelService::class => Service\DomaineFonctionnelServiceFactory::class,
    ],


    'forms' => [
        Form\DomaineFonctionnel\DomaineFonctionnelSaisieForm::class => Form\DomaineFonctionnel\DomaineFonctionnelSaisieFormFactory::class,
    ],
];
