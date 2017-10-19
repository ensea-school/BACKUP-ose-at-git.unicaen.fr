<?php

namespace Application;

use Application\Assertion\ChargensAssertion;
use UnicaenAuth\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'router' => [
        'routes' => [
            'chargens' => [
                'type'          => 'Segment',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/chargens',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Chargens',
                        'action'        => 'INDEX',
                    ],
                ],
                'child_routes'  => [

                    'scenario' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/scenario',
                            'defaults' => [
                                'action' => 'scenario',
                            ],
                        ],
                        'child_routes'  => [
                            'saisir'    => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/saisir[/:scenario]',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'scenario-saisir',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'dupliquer' => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/dupliquer[/:scenario]',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'scenario-dupliquer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'supprimer' => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'       => '/supprimer/:scenario',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'scenario-supprimer',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],

                    'seuil' => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/seuil[/:scenario]',
                            'constraints' => [
                                'scenario' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'seuil',
                            ],
                        ],
                        'child_routes'  => [
                            'modifier'    => [
                                'type'          => 'Literal',
                                'options'       => [
                                    'route'    => '/modifier',
                                    'defaults' => [
                                        'action' => 'seuil-modifier',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'calc-heures' => [
                                'type'          => 'Segment',
                                'options'       => [
                                    'route'    => '/calc-heures',
                                    'defaults' => [
                                        'action' => 'seuil-calc-heures',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],

                    'formation' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/formation',
                            'defaults' => [
                                'action' => 'formation',
                            ],
                        ],
                        'child_routes'  => [
                            'json'        => [
                                'type'          => 'Literal',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'    => '/json',
                                    'defaults' => [
                                        'action' => 'formation-json',
                                    ],
                                ],
                            ],
                            'enregistrer' => [
                                'type'          => 'Literal',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'    => '/enregistrer',
                                    'defaults' => [
                                        'action' => 'formation-enregistrer',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'export' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/export',
                            'defaults' => [
                                'action' => 'export',
                            ],
                        ],
                        'child_routes'  => [
                            'csv' => [
                                'type'          => 'Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'       => '/csv[/:scenario]',
                                    'constraints' => [
                                        'scenario' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'export-csv',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'depassement' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/depassement',
                            'defaults' => [
                                'action' => 'depassement',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation'   => [
        'default' => [
            'home' => [
                'pages' => [
                    'chargens' => [
                        'label'    => "Charges",
                        'title'    => "Charges d'enseignement",
                        'route'    => 'chargens',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Chargens', 'index'),
                        'order'    => 5,
                        'pages'    => [
                            'scenario'  => [
                                'label'       => "Gestion des scénarios",
                                'description' => "Permet de créer, dupliquer ou supprimer des scénarios",
                                'route'       => 'chargens/scenario',
                                'resource'    => PrivilegeController::getResourceId('Application\Controller\Chargens', 'scenario'),
                                'visible'     => false,
                            ],
                            'seuil'     => [
                                'label'       => "Gestion des seuils de dédoublement",
                                'description' => "Permet de spécifier des seuils de dédoublement qui s'appliqueront à toutes les formations concernées",
                                'route'       => 'chargens/seuil',
                                'resource'    => PrivilegeController::getResourceId('Application\Controller\Chargens', 'seuil'),
                                'visible'     => false,
                            ],
                            'formation' => [
                                'label'       => "Paramétrage des formations",
                                'description' => "Permet de configurer de manière fine les formations (définition des taux d'assiduite, seuils, effectifs...)",
                                'route'       => 'chargens/formation',
                                'resource'    => PrivilegeController::getResourceId('Application\Controller\Chargens', 'formation'),
                                'visible'     => false,
                            ],
                            'export'    => [
                                'label'       => "Export au format CSV (pour tableur)",
                                'description' => "Permet de récupérer les données liées aux charges d'enseignement",
                                'route'       => 'chargens/export',
                                'resource'    => PrivilegeController::getResourceId('Application\Controller\Chargens', 'export'),
                                'visible'     => false,
                            ],
                            'depassement'    => [
                                'label'       => "Dépassement des charges par les services au format CSV (pour tableur)",
                                'description' => "Permet de récupérer les données liées aux dépassement des heures de service par rapport aux charges d'enseignement",
                                'route'       => 'chargens/depassement',
                                'resource'    => PrivilegeController::getResourceId('Application\Controller\Chargens', 'depassement'),
                                'visible'     => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /* Droits d'accès */
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::CHARGENS_VISUALISATION,
                    ],
                ],

                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['scenario'],
                    'privileges' => [
                        Privileges::CHARGENS_SCENARIO_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['scenario-saisir', 'scenario-dupliquer', 'scenario-supprimer'],
                    'privileges' => [
                        Privileges::CHARGENS_SCENARIO_COMPOSANTE_EDITION,
                        Privileges::CHARGENS_SCENARIO_ETABLISSEMENT_EDITION,
                    ],
                    'assertion'  => 'assertionChargens',
                ],
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['seuil', 'seuil-calc-heures'],
                    'privileges' => [
                        Privileges::CHARGENS_SEUIL_COMPOSANTE_VISUALISATION,
                        Privileges::CHARGENS_SEUIL_ETABLISSEMENT_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['seuil-modifier'],
                    'privileges' => [
                        Privileges::CHARGENS_SEUIL_ETABLISSEMENT_EDITION,
                        Privileges::CHARGENS_SEUIL_COMPOSANTE_EDITION,
                    ],
                ],

                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['formation', 'formation-json'],
                    'privileges' => [
                        Privileges::CHARGENS_FORMATION_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['formation-enregistrer'],
                    'privileges' => [
                        Privileges::CHARGENS_FORMATION_ASSIDUITE_EDITION,
                        Privileges::CHARGENS_FORMATION_EFFECTIFS_EDITION,
                        Privileges::CHARGENS_FORMATION_SEUILS_EDITION,
                        Privileges::CHARGENS_FORMATION_ACTIF_EDITION,
                        Privileges::CHARGENS_FORMATION_POIDS_EDITION,
                        Privileges::CHARGENS_FORMATION_CHOIX_EDITION,
                    ],
                ],

                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['export', 'export-csv'],
                    'privileges' => [
                        Privileges::CHARGENS_EXPORT_CSV,
                    ],
                ],

                [
                    'controller' => 'Application\Controller\Chargens',
                    'action'     => ['depassement'],
                    'privileges' => [
                        Privileges::CHARGENS_DEPASSEMENT_CSV,
                    ],
                ],
            ],
        ],

        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'Scenario' => [],
            ],
        ],

        'rule_providers' => [
            \UnicaenAuth\Provider\Rule\PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => ChargensAssertion::SCENARIO_EDITION,
                        'resources'  => ['Scenario', 'Structure'],
                        'assertion'  => 'AssertionChargens',
                    ],
                    [
                        'privileges' => [
                            Privileges::CHARGENS_SEUIL_ETABLISSEMENT_EDITION,
                            Privileges::CHARGENS_SEUIL_COMPOSANTE_EDITION,
                        ],
                        'resources'  => ['Structure'],
                        'assertion'  => 'AssertionChargens',
                    ],
                ],
            ],
        ],
    ],

    /* Déclaration du contrôleur */
    'controllers'  => [
        'factories' => [
            'Application\Controller\Chargens' => Controller\Factory\ChargensControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'applicationScenario' => Service\ScenarioService::class,
            'assertionChargens'   => Assertion\ChargensAssertion::class,
        ],
        'factories'  => [
            'applicationSeuilCharge' => Service\Factory\SeuilChargeServiceFactory::class,
            'chargens'               => Provider\Chargens\ChargensProviderFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'chargens' => View\Helper\Chargens\ChargensViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'chargensFiltre'              => Form\Chargens\FiltreForm::class,
            'chargensScenarioFiltre'      => Form\Chargens\ScenarioFiltreForm::class,
            'chargensDuplicationScenario' => Form\Chargens\DuplicationScenarioForm::class,
            'chargensScenario'            => Form\Chargens\ScenarioForm::class,
        ],
    ],
];