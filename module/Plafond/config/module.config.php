<?php

namespace Plafond;

use Application\Provider\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Plafond\View\Helper\PlafondConfigElementViewHelperFactory;

return [

    'routes' => [
        'plafond' => [
            'route'         => '/plafond',
            'controller'    => Controller\PlafondController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'ajouter'   => [
                    'route'      => '/ajouter',
                    'controller' => Controller\PlafondController::class,
                    'action'     => 'editer',
                ],
                'modifier'  => [
                    'route'       => '/modifier/:plafond',
                    'controller'  => Controller\PlafondController::class,
                    'action'      => 'editer',
                    'constraints' => [
                        'plafond' => '[0-9]*',
                    ],
                ],
                'supprimer' => [
                    'route'       => '/supprimer/:plafond',
                    'controller'  => Controller\PlafondController::class,
                    'action'      => 'supprimer',
                    'constraints' => [
                        'plafond' => '[0-9]*',
                    ],
                ],
                'plafonds'  => [
                    'route'       => '/plafonds/:id/:class/:typeVolumeHoraire',
                    'controller'  => Controller\PlafondController::class,
                    'action'      => 'plafonds',
                    'constraints' => [
                        'id'                => '[0-9]*',
                        'typeVolumeHoraire' => '[0-9]*',
                    ],
                ],

                'config-structure' => [
                    'route'      => '/config/structure',
                    'controller' => Controller\PlafondController::class,
                    'action'     => 'config-structure',
                ],

                'config-statut' => [
                    'route'      => '/config/statut',
                    'controller' => Controller\PlafondController::class,
                    'action'     => 'config-statut',
                ],

                'config-referentiel' => [
                    'route'      => '/config/referentiel',
                    'controller' => Controller\PlafondController::class,
                    'action'     => 'config-referentiel',
                ],

                'config-mission' => [
                    'route'      => '/config/mission',
                    'controller' => Controller\PlafondController::class,
                    'action'     => 'config-mission',
                ],


                'construire-calculer' => [
                    'route'      => '/construire-calculer',
                    'controller' => Controller\PlafondController::class,
                    'action'     => 'construire-calculer',
                ],

                'structure' => [
                    'route'         => '/structure/:structure',
                    'controller'    => Controller\PlafondController::class,
                    'action'        => 'index-structure',
                    'constraints'   => [
                        'structure' => '[0-9]*',
                    ],
                    'may_terminate' => true,
                ],

                'referentiel' => [
                    'route'         => '/referentiel/:fonctionReferentiel',
                    'controller'    => Controller\PlafondController::class,
                    'action'        => 'index-referentiel',
                    'constraints'   => [
                        'fonctionReferentiel' => '[0-9]*',
                    ],
                    'may_terminate' => true,
                ],

                'mission' => [
                    'route'         => '/mission/:typeMission',
                    'controller'    => Controller\PlafondController::class,
                    'action'        => 'index-mission',
                    'constraints'   => [
                        'typeMission' => '[0-9]*',
                    ],
                    'may_terminate' => true,
                ],
            ],
        ],

        'derogations' => [
            'route'         => '/intervenant/:intervenant/derogations',
            'controller'    => Controller\DerogationController::class,
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
            'controller' => Controller\PlafondController::class,
            'action'     => 'construire',
        ],
        'calculer'   => [
            'route'      => 'plafonds calculer',
            'controller' => Controller\PlafondController::class,
            'action'     => 'calculer',
        ],
    ],

    'navigation' => [
        'administration'    => [
            'pages' => [
                'rh' => [
                    'pages' => [
                        'plafonds' => [
                            'label' => "Plafonds",
                            'route' => 'plafond',
                            'color' => '#9B9B9B',
                            'order' => 60,
                        ],
                    ],
                ],
            ],
        ],
        'intervenant-admin' => [
            'pages' => [
                'derogations' => [
                    'label' => 'Dérogations',
                    'icon'  => 'fas fa-shuffle',
                    'route' => 'derogations',
                    'order' => 5,
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_VISUALISATION,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['editer',
                             'supprimer',
                             'construire',
                             'calculer',
                             'construire-calculer'],
            'privileges' => Privileges::PLAFONDS_EDITION,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['plafonds'],
            'privileges' => [
                Privileges::PLAFONDS_VISUALISATION,
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
                Privileges::MISSION_EDITION_REALISE,
                Privileges::MISSION_EDITION,
            ],
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['config-structure'],
            'privileges' => Privileges::PLAFONDS_CONFIG_STRUCTURE,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['config-statut'],
            'privileges' => Privileges::PLAFONDS_CONFIG_STATUT,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['config-referentiel'],
            'privileges' => Privileges::PLAFONDS_CONFIG_REFERENTIEL,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['config-mission'],
            'privileges' => Privileges::PLAFONDS_CONFIG_MISSION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['index-structure'],
            'privileges' => Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['index-referentiel'],
            'privileges' => Privileges::REFERENTIEL_ADMIN_VISUALISATION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\PlafondController::class,
            'action'     => ['index-mission'],
            'privileges' => Privileges::MISSION_VISUALISATION_TYPE,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => Controller\DerogationController::class,
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_DEROGATIONS_VISUALISATION,
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::PLAFONDS_DEROGATIONS_EDITION,
            ],
            'resources'  => Intervenant::class,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
    ],

    'controllers' => [
        Controller\PlafondController::class    => Controller\PlafondControllerFactory::class,
        Controller\DerogationController::class => Controller\DerogationControllerFactory::class,
    ],

    'services' => [
        Service\PlafondService::class     => Service\PlafondServiceFactory::class,
        Processus\PlafondProcessus::class => Processus\PlafondProcessusFactory::class,
        Service\IndicateurService::class  => Service\IndicateurServiceFactory::class,
    ],

    'forms' => [
        Form\PlafondForm::class           => Form\PlafondFormFactory::class,
        Form\PlafondConfigFieldset::class => Form\PlafondConfigFieldsetFactory::class,
    ],

    'view_helpers' => [
        'plafondConfig' => PlafondConfigElementViewHelperFactory::class,
        'plafonds'      => View\Helper\PlafondsViewHelperFactory::class,
    ],
];