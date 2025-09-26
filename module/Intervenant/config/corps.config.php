<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Intervenant;


use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'corps' => [
            'route'         => '/corps',
            'controller'    => Controller\CorpsController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::NOMENCLATURE_RH_CORPS_VISUALISATION],
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'    => [
                    'route'       => '/saisie[/:corps]',
                    'constraints' => [
                        'corps' => '[0-9]*',
                    ],
                    'controller'  => Controller\CorpsController::class,
                    'action'      => 'saisie',
                    'privileges'  => [Privileges::NOMENCLATURE_RH_CORPS_EDITION],
                ],
                'supprimer' => [
                    'route'       => '/supprimer/:corps',
                    'constraints' => [
                        'corps' => '[0-9]*',
                    ],
                    'controller'  => Controller\CorpsController::class,
                    'action'      => 'supprimer',
                    'privileges'  => [Privileges::NOMENCLATURE_RH_CORPS_EDITION],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'rh' => [
                    'pages' => [
                        'corps' => [
                            'label'    => 'Corps',
                            'route'    => 'corps',
                            'resource' => Authorize::controllerResource(Controller\CorpsController::class, 'index'),
                            'order'    => 10,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\CorpsController::class => Controller\CorpsControllerFactory::class,
    ],
    'forms'       => [
        Form\CorpsSaisieForm::class => Form\CorpsSaisieFormFactory::class,
    ],
    'services' => [
        Service\CorpsService::class    => Service\CorpsServiceFactory::class,
    ],
];
