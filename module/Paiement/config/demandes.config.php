<?php

namespace Paiement;

use Application\Provider\Privileges;
use Workflow\Entity\Db\WorkflowEtape;

return [
    'routes' => [
        'paiement'    => [
            'child_routes'  => [
                'demande-mise-en-paiement-lot'         => [
                    'route'       => '/demande-mise-en-paiement-lot[/:structure]',
                    'controller'  => Controller\DemandesController::class,
                    'action'      => 'demandeMiseEnPaiementLot',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'privileges'  => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                'ajouter-demande-mise-en-paiement'     => [
                    'route'      => '/:intervenant/ajouter-demandes',
                    'controller' => Controller\DemandesController::class,
                    'action'     => 'ajouterDemandesMiseEnPaiement',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                'supprimer-demande-mise-en-paiement'   => [
                    'route'      => '/:intervenant/supprimer-demande/:mise-en-paiement',
                    'controller' => Controller\DemandesController::class,
                    'action'     => 'supprimerDemandeMiseEnPaiement',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
                'process-demande-mise-en-paiement-lot' => [
                    'route'      => '/process-demande-mise-en-paiement-lot',
                    'controller' => Controller\DemandesController::class,
                    'action'     => 'processDemandeMiseEnPaiementLot',
                    'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    'assertion'  => Assertion\PaiementAssertion::class,
                ],
            ],
        ],
        'intervenant' => [
            'child_routes' => [
                'mise-en-paiement' => [
                    'child_routes'  => [
                        'demande'                       => [
                            'route'      => '/demande',
                            'controller' => Controller\DemandesController::class,
                            'action'     => 'demandeMiseEnPaiement',
                            'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                            'assertion'  => Assertion\PaiementAssertion::class,
                        ],
                        'get-demandes-mise-en-paiement' => [
                            'route'      => '/get-demandes-mise-en-paiement[/:structure]',
                            'controller' => Controller\DemandesController::class,
                            'action'     => 'getDemandesMiseEnpaiement',
                            'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                            'assertion'  => Assertion\PaiementAssertion::class,
                        ],


                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                'demande-mise-en-paiement' => [
                    'label'               => "Demande de mise en paiement",
                    'title'               => "Demande de mise en paiement",
                    'route'               => 'intervenant/mise-en-paiement/demande',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'withtarget'          => true,
                    'workflow-etape-code' => WorkflowEtape::DEMANDE_MEP,
                    'order'               => 16,
                ],
            ],
        ],
        'gestion'     => [
            'pages' => [
                'paiement' => [
                    'pages'    => [
                        'demande-mise-en-paiement-lot' => [
                            'label' => "Demande de mise en paiement par lot",
                            'title' => "Permet de demander l'ensemble des mises en paiement pour une structure",
                            'route' => 'paiement/demande-mise-en-paiement-lot',
                        ],
                    ],
                ],
            ],
        ],
    ],


    'services' => [
        Service\DemandesService::class => Service\DemandesServiceFactory::class,
    ],

    'controllers' => [
        Controller\DemandesController::class => Controller\DemandesControllerFactory::class
    ],
];