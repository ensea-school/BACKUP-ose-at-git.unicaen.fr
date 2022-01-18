<?php

namespace Plafond;

use Application\Provider\Privilege\Privileges;
use Plafond\View\Helper\PlafondConfigElementViewHelperFactory;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;

return [

    'routes' => [
        'plafond' => [
            'route'         => '/plafond',
            'controller'    => 'Plafond\Controller\Plafond',
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'ajouter'   => [
                    'route'  => '/ajouter',
                    'action' => 'editer',
                ],
                'modifier'  => [
                    'route'       => '/modifier/:plafond',
                    'action'      => 'editer',
                    'constraints' => [
                        'plafond' => '[0-9]*',
                    ],
                ],
                'supprimer' => [
                    'route'       => '/supprimer/:plafond',
                    'action'      => 'supprimer',
                    'constraints' => [
                        'plafond' => '[0-9]*',
                    ],
                ],

                'config-application' => [
                    'route'  => '/config/application',
                    'action' => 'config-application',
                ],

                'config-structure' => [
                    'route'  => '/config/structure',
                    'action' => 'config-structure',
                ],

                'config-statut' => [
                    'route'  => '/config/statut',
                    'action' => 'config-statut',
                ],

                'config-referentiel' => [
                    'route'  => '/config/referentiel',
                    'action' => 'config-referentiel',
                ],

                'construire-calculer' => [
                    'route'  => '/construire-calculer',
                    'action' => 'construire-calculer',
                ],

                'structure' => [
                    'route'         => '/structure/:structure',
                    'controller'    => 'Plafond\Controller\PlafondStructure',
                    'action'        => 'index',
                    'constraints'   => [
                        'structure' => '[0-9]*',
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'ajouter'  => [
                            'route'  => '/ajouter',
                            'action' => 'editer',
                        ],
                        'modifier' => [
                            'route'  => '/modifier',
                            'action' => 'editer',
                        ],
                    ],
                ],
            ],
        ],

        'derogations' => [
            'route'         => '/intervenant/:intervenant/derogations',
            'controller'    => 'Plafond\Controller\Derogation',
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                /* Placez ici vos routes filles */
            ],
        ],
    ],

    'console' => [
        'construire' => [
            'route'      => 'plafonds construire',
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => 'construire',
        ],
        'calculer'   => [
            'route'      => 'plafonds calculer',
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => 'calculer',
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'plafonds' => [
                    'icon'         => 'glyphicon glyphicon-wrench',
                    'label'        => "Plafonds",
                    'route'        => 'plafond',
                    'resource'     => PrivilegeController::getResourceId('Plafond\Controller\Plafond', 'index'),
                    'border-color' => '#9B9B9B',
                    'order'        => 120,
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_VISUALISATION,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['editer', 'supprimer', 'construire', 'calculer', 'construire-calculer'],
            'privileges' => Privileges::PLAFONDS_EDITION,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['config-application'],
            'privileges' => Privileges::PLAFONDS_CONFIG_APPLICATION,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['config-structure'],
            'privileges' => Privileges::PLAFONDS_CONFIG_STRUCTURE,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['config-statut'],
            'privileges' => Privileges::PLAFONDS_CONFIG_STATUT,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['config-referentiel'],
            'privileges' => Privileges::PLAFONDS_CONFIG_REFERENTIEL,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\PlafondStructure',
            'action'     => ['index'],
            'privileges' => Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Derogation',
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_DEROGATIONS_VISUALISATION,
        ],
    ],

    /*    'rules' => [
            [
                'privileges' => [

                ],
                'resources'  => 'Structure',
                'assertion'  => Assertion\PlafondAssertion::class,
            ],
        ],*/

    'controllers' => [
        'Plafond\Controller\Plafond'          => Controller\PlafondControllerFactory::class,
        'Plafond\Controller\PlafondStructure' => Controller\PlafondStructureControllerFactory::class,
        'Plafond\Controller\Derogation'       => Controller\DerogationControllerFactory::class,
    ],

    'services' => [
        Assertion\PlafondAssertion::class      => AssertionFactory::class,
        Service\PlafondService::class          => Service\PlafondServiceFactory::class,
        Service\PlafondStructureService::class => Service\PlafondStructureServiceFactory::class,
        Processus\PlafondProcessus::class      => Processus\PlafondProcessusFactory::class,
    ],

    'forms' => [
        Form\PlafondForm::class => Form\PlafondFormFactory::class,
    ],

    'view_helpers' => [
        'plafondConfig' => PlafondConfigElementViewHelperFactory::class,
    ],
];