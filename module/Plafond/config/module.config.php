<?php

namespace Plafond;

use Application\Provider\Privileges;
use Plafond\View\Helper\PlafondConfigElementViewHelperFactory;

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
                'plafonds'  => [
                    'route'       => '/plafonds/:id/:class/:typeVolumeHoraire',
                    'action'      => 'plafonds',
                    'constraints' => [
                        'id'                => '[0-9]*',
                        'typeVolumeHoraire' => '[0-9]*',
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

                'config-mission' => [
                    'route'  => '/config/mission',
                    'action' => 'config-mission',
                ],

                'construire-calculer' => [
                    'route'  => '/construire-calculer',
                    'action' => 'construire-calculer',
                ],

                'structure' => [
                    'route'         => '/structure/:structure',
                    'controller'    => 'Plafond\Controller\Plafond',
                    'action'        => 'index-structure',
                    'constraints'   => [
                        'structure' => '[0-9]*',
                    ],
                    'may_terminate' => true,
                ],

                'referentiel' => [
                    'route'         => '/referentiel/:fonctionReferentiel',
                    'controller'    => 'Plafond\Controller\Plafond',
                    'action'        => 'index-referentiel',
                    'constraints'   => [
                        'fonctionReferentiel' => '[0-9]*',
                    ],
                    'may_terminate' => true,
                ],

                'mission' => [
                    'route'         => '/mission/:typeMission',
                    'controller'    => 'Plafond\Controller\Plafond',
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
                'rh' => [
                    'pages' => [
                        'plafonds' => [
                            'label'    => "Plafonds",
                            'route'    => 'plafond',
                            'color'    => '#9B9B9B',
                            'order'    => 60,
                        ],
                    ],
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
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['config-mission'],
            'privileges' => Privileges::PLAFONDS_CONFIG_MISSION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['index-structure'],
            'privileges' => Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['index-referentiel'],
            'privileges' => Privileges::REFERENTIEL_ADMIN_VISUALISATION,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['index-mission'],
            'privileges' => Privileges::MISSION_VISUALISATION_TYPE,
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
        [
            'controller' => 'Plafond\Controller\Derogation',
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_DEROGATIONS_VISUALISATION,
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::PLAFONDS_DEROGATIONS_EDITION,
            ],
            'resources'  => 'Intervenant',
            'assertion'  => Assertion\PlafondAssertion::class,
        ],
    ],

    'controllers' => [
        'Plafond\Controller\Plafond'    => Controller\PlafondControllerFactory::class,
        'Plafond\Controller\Derogation' => Controller\DerogationControllerFactory::class,
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