<?php

namespace Lieu;

use Framework\Authorize\Authorize;
use Application\Provider\Privilege\Privileges;

return [
    'routes' => [
        'departement' => [
            'route'         => '/departement',
            'controller'    => Controller\DepartementController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::PARAMETRES_DEPARTEMENT_VISUALISATION],
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'    => [
                    'route'      => '/saisie[/:departement]',
                    'controller' => Controller\DepartementController::class,
                    'action'     => 'saisie',
                    'privileges' => [Privileges::PARAMETRES_DEPARTEMENT_EDITION],
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:departement',
                    'controller' => Controller\DepartementController::class,
                    'action'     => 'supprimer',
                    'privileges' => [Privileges::PARAMETRES_DEPARTEMENT_EDITION],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'nomenclatures' => [
                    'pages' => [
                        'gestion-departement' => [
                            'label'          => 'Départements',
                            'route'          => 'departement',
                            'resource'       => Authorize::controllerResource(Controller\DepartementController::class, 'index'),
                            'order'          => 10,
                            'border - color' => '#111',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\DepartementController::class => Controller\DepartementControllerFactory::class,
    ],
    'forms'       => [
        Form\DepartementSaisieForm::class => Form\DepartementSaisieFormFactory::class,
    ],
    'services'    => [
        Service\DepartementService::class => Service\DepartementServiceFactory::class,
    ],
];
