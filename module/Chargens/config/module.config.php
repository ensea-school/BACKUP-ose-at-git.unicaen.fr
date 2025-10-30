<?php

namespace Chargens;

use Application\Provider\Privileges;
use Chargens\Assertion\ChargensAssertion;
use Chargens\Entity\Db\Scenario;
use Lieu\Entity\Db\Structure;
use Unicaen\Framework\Authorize\Authorize;

return [
    'routes' => [
        'chargens' => [
            'route'         => '/chargens',
            'controller'    => Controller\ChargensController::class,
            'action'        => 'index',
            'privileges'    => [
                Privileges::CHARGENS_VISUALISATION,
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'scenario' => [
                    'route'         => '/scenario',
                    'controller'    => Controller\ChargensController::class,
                    'action'        => 'scenario',
                    'privileges'    => [
                        Privileges::CHARGENS_SCENARIO_VISUALISATION,
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'saisir'    => [
                            'route'       => '/saisir[/:scenario]',
                            'constraints' => [
                                'scenario' => '[0-9]*',
                            ],
                            'controller'  => Controller\ChargensController::class,
                            'action'      => 'scenario-saisir',
                            'privileges'  => [
                                Privileges::CHARGENS_SCENARIO_COMPOSANTE_EDITION,
                                Privileges::CHARGENS_SCENARIO_ETABLISSEMENT_EDITION,
                            ],
                            'assertion'   => Assertion\ChargensAssertion::class,
                        ],
                        'dupliquer' => [
                            'route'       => '/dupliquer[/:scenario]',
                            'constraints' => [
                                'scenario' => '[0-9]*',
                            ],
                            'controller'  => Controller\ChargensController::class,
                            'action'      => 'scenario-dupliquer',
                            'privileges'  => [
                                Privileges::CHARGENS_SCENARIO_COMPOSANTE_EDITION,
                                Privileges::CHARGENS_SCENARIO_ETABLISSEMENT_EDITION,
                            ],
                            'assertion'   => Assertion\ChargensAssertion::class,
                        ],
                        'supprimer' => [
                            'route'       => '/supprimer/:scenario',
                            'constraints' => [
                                'scenario' => '[0-9]*',
                            ],
                            'controller'  => Controller\ChargensController::class,
                            'action'      => 'scenario-supprimer',
                            'privileges'  => [
                                Privileges::CHARGENS_SCENARIO_COMPOSANTE_EDITION,
                                Privileges::CHARGENS_SCENARIO_ETABLISSEMENT_EDITION,
                            ],
                            'assertion'   => Assertion\ChargensAssertion::class,
                        ],
                    ],
                ],

                'seuil' => [
                    'may_terminate' => true,
                    'route'         => '/seuil[/:scenario]',
                    'constraints'   => [
                        'scenario' => '[0-9]*',
                    ],
                    'controller'    => Controller\ChargensController::class,
                    'action'        => 'seuil',
                    'privileges'    => [
                        Privileges::CHARGENS_SEUIL_COMPOSANTE_VISUALISATION,
                        Privileges::CHARGENS_SEUIL_ETABLISSEMENT_VISUALISATION,
                    ],
                    'child_routes'  => [
                        'modifier'    => [
                            'route'      => '/modifier',
                            'controller' => Controller\ChargensController::class,
                            'action'     => 'seuil-modifier',
                            'privileges' => [
                                Privileges::CHARGENS_SEUIL_ETABLISSEMENT_EDITION,
                                Privileges::CHARGENS_SEUIL_COMPOSANTE_EDITION,
                            ],
                        ],
                        'calc-heures' => [
                            'route'      => '/calc-heures',
                            'controller' => Controller\ChargensController::class,
                            'action'     => 'seuil-calc-heures',
                            'privileges' => [
                                Privileges::CHARGENS_SEUIL_COMPOSANTE_VISUALISATION,
                                Privileges::CHARGENS_SEUIL_ETABLISSEMENT_VISUALISATION,
                            ],
                        ],
                    ],
                ],

                'formation' => [
                    'may_terminate' => true,
                    'route'         => '/formation',
                    'controller'    => Controller\ChargensController::class,
                    'action'        => 'formation',
                    'privileges'    => [
                        Privileges::CHARGENS_FORMATION_VISUALISATION,
                    ],
                    'child_routes'  => [
                        'json'        => [
                            'route'      => '/json',
                            'controller' => Controller\ChargensController::class,
                            'action'     => 'formation-json',
                            'privileges' => [
                                Privileges::CHARGENS_FORMATION_VISUALISATION,
                            ],
                        ],
                        'enregistrer' => [
                            'route'      => '/enregistrer',
                            'controller' => Controller\ChargensController::class,
                            'action'     => 'formation-enregistrer',
                            'privileges' => [
                                Privileges::CHARGENS_FORMATION_ASSIDUITE_EDITION,
                                Privileges::CHARGENS_FORMATION_EFFECTIFS_EDITION,
                                Privileges::CHARGENS_FORMATION_SEUILS_EDITION,
                                Privileges::CHARGENS_FORMATION_ACTIF_EDITION,
                                Privileges::CHARGENS_FORMATION_POIDS_EDITION,
                                Privileges::CHARGENS_FORMATION_CHOIX_EDITION,
                            ],
                        ],
                    ],
                ],

                'export' => [
                    'route'         => '/export',
                    'controller'    => Controller\ChargensController::class,
                    'action'        => 'export',
                    'privileges'    => [
                        Privileges::CHARGENS_EXPORT_CSV,
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'csv' => [
                            'route'       => '/csv[/:scenario]',
                            'constraints' => [
                                'scenario' => '[0-9]*',
                            ],
                            'controller'  => Controller\ChargensController::class,
                            'action'      => 'export-csv',
                            'privileges'  => [
                                Privileges::CHARGENS_EXPORT_CSV,
                            ],
                        ],
                    ],
                ],

                'depassement' => [
                    'route'      => '/depassement',
                    'controller' => Controller\ChargensController::class,
                    'action'     => 'depassement',
                    'privileges' => [
                        Privileges::CHARGENS_DEPASSEMENT_CSV,
                    ],
                ],

                'differentiel' => [
                    'route'      => '/diffrentiel',
                    'controller' => Controller\ChargensController::class,
                    'action'     => 'differentiel',
                    'privileges' => [
                        Privileges::CHARGENS_EXPORT_CSV,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'chargens' => [
            'label'    => "Charges",
            'title'    => "Charges d'enseignement",
            'route'    => 'chargens',
            'resource' => Authorize::controllerResource(Controller\ChargensController::class, 'index'),
            'order'    => 4,
            'pages'    => [
                'scenario'     => [
                    'label'       => "Gestion des scénarios",
                    'description' => "Permet de créer, dupliquer ou supprimer des scénarios",
                    'route'       => 'chargens/scenario',
                ],
                'seuil'        => [
                    'label'       => "Gestion des seuils de dédoublement",
                    'description' => "Permet de spécifier des seuils de dédoublement qui s'appliqueront à toutes les formations concernées",
                    'route'       => 'chargens/seuil',
                ],
                'formation'    => [
                    'label'       => "Paramétrage des formations",
                    'description' => "Permet de configurer de manière fine les formations (définition des taux d'assiduite, seuils, effectifs...)",
                    'route'       => 'chargens/formation',
                ],
                'export'       => [
                    'label'       => "Export des charges d'enseignement (CSV)",
                    'description' => "Produit un fichier qui comporte l'ensemble des données concernant les charges d'enseignement",
                    'route'       => 'chargens/export',
                ],
                'differentiel' => [
                    'label'       => "Différentiel entre deux exports des charges d'enseignement",
                    'description' => "Affiche les différences entre deux exports des charges d'enseignement",
                    'route'       => 'chargens/differentiel',
                ],
                'depassement'  => [
                    'label'       => "Rapprochement des charges et des services d'enseignement (CSV)",
                    'description' => "Produit un fichier qui rapproche les services d'enseignement saisis et les charges d'enseignement calculées",
                    'route'       => 'chargens/depassement',
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => ChargensAssertion::SCENARIO_EDITION,
            'resources'  => [Scenario::class, Structure::class],
            'assertion'  => Assertion\ChargensAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::CHARGENS_SEUIL_ETABLISSEMENT_EDITION,
                Privileges::CHARGENS_SEUIL_COMPOSANTE_EDITION,
            ],
            'resources'  => [Structure::class],
            'assertion'  => Assertion\ChargensAssertion::class,
        ],
    ],

    'controllers' => [
        Controller\ChargensController::class => Controller\ChargensControllerFactory::class,
    ],

    'services' => [
        Service\ScenarioService::class               => Service\ScenarioServiceFactory::class,
        Service\SeuilChargeService::class            => Service\SeuilChargeServiceFactory::class,
        Provider\ChargensProvider::class             => Provider\ChargensProviderFactory::class,
        Command\ChargensCalculEffectifCommand::class => Command\ChargensCalculEffectifCommandFactory::class,
    ],

    'view_helpers' => [
        'chargens' => View\Helper\ChargensViewHelperFactory::class,
    ],

    'forms' => [
        Form\FiltreForm::class              => Form\FiltreFormFactory::class,
        Form\ScenarioFiltreForm::class      => Form\ScenarioFiltreFormFactory::class,
        Form\DuplicationScenarioForm::class => Form\DuplicationScenarioFormFactory::class,
        Form\ScenarioForm::class            => Form\ScenarioFormFactory::class,
        Form\DifferentielForm::class        => Form\DifferentielFormFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'chargens-calcul-effectifs' => Command\ChargensCalculEffectifCommand::class,
        ],
    ],

];