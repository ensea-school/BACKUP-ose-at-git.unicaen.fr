<?php

namespace Lieu;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;
use Lieu\Controller\EtablissementController;

return [
    'routes' => [
        'etablissement' => [
            'route'         => '/etablissement',
            'controller'    => Controller\EtablissementController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::PARAMETRES_ETABLISSEMENT_VISUALISATION],
            'may_terminate' => true,
            'child_routes'  => [
                'modifier'  => [
                    'route'       => '/modifier/:id',
                    'controller'  => Controller\EtablissementController::class,
                    'action'      => 'modifier',
                    'constraints' => [
                        'id' => '[0-9]*',
                    ],

                ],
                'recherche' => [
                    'route'      => '/recherche[/:term]',
                    'controller' => Controller\EtablissementController::class,
                    'action'     => 'recherche',
                ],
                'saisie'    => [
                    'route'      => '/saisie[/:etablissement]',
                    'controller' => Controller\EtablissementController::class,
                    'action'     => 'saisie',
                    'privileges' => [Privileges::PARAMETRES_ETABLISSEMENT_EDITION],
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:etablissement',
                    'controller' => Controller\EtablissementController::class,
                    'action'     => 'supprimer',
                    'privileges' => [Privileges::PARAMETRES_ETABLISSEMENT_EDITION],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => EtablissementController::class,
            'action'     => ['recherche'],
            'roles'      => ['user'],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'nomenclatures' => [
                    'pages' => [
                        'gestion-etablissement' => [
                            'label'          => 'Établissements',
                            'route'          => 'etablissement',
                            'resource'       => Authorize::controllerResource(Controller\EtablissementController::class, 'index'),
                            'order'          => 20,
                            'border - color' => '#111',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\EtablissementController::class => Controller\EtablissementControllerFactory::class,
    ],

    'forms' => [
        Form\EtablissementSaisieForm::class => Form\EtablissementSaisieFormFactory::class,
    ],

    'services' => [
        Service\EtablissementService::class => Service\EtablissementServiceFactory::class,
    ],

    'view_helpers' => [
        'etablissement' => View\Helper\EtablissementViewHelperFactory::class,
    ],
];
