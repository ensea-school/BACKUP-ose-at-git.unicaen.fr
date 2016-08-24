<?php

namespace Application;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'paiement' => [
                'type'          => 'Literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/paiement',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Paiement',
                        'action'        => 'index',
                    ],
                ],
                'child_routes'  => [
                    'etat-demande-paiement' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/etat-demande-paiement',
                            'defaults' => [
                                'action' => 'etatPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::A_METTRE_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'mise-en-paiement'      => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'       => '/mise-en-paiement/:structure/:intervenants',
                            'constraints' => [
                                'structure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'MiseEnPaiement',
                            ],
                        ],
                    ],
                    'etat-paiement'         => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/etat-paiement',
                            'defaults' => [
                                'action' => 'etatPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'mises-en-paiement-csv' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/mises-en-paiement-csv',
                            'defaults' => [
                                'action' => 'misesEnPaiementCsv',
                            ],
                        ],
                    ],
                    'extraction-winpaie'    => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/extraction-winpaie[/:type][/:periode]',
                            'defaults' => [
                                'action' => 'extractionWinpaie',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
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
                                'workflow-etape-code' => WfEtape::CODE_DEMANDE_MEP,
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Paiement', 'demandeMiseEnPaiement'),
                                'visible'             => 'assertionPaiement',
                            ],
                            'visualisation-mise-en-paiement' => [
                                'label'               => "Visualisation des mises en paiement",
                                'title'               => "Visualisation des mises en paiement",
                                'route'               => 'intervenant/mise-en-paiement/visualisation',
                                'paramsInject'        => [
                                    'intervenant',
                                ],
                                'withtarget'          => true,
                                'workflow-etape-code' => WfEtape::CODE_SAISIE_MEP,
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Paiement', 'visualisationMiseEnPaiement'),
                                'visible'             => 'assertionPaiement',
                            ],
                            'edition-mise-en-paiement' => [
                                'label'               => "Annulation de mises en paiement",
                                'title'               => "Annulation de mises en paiement",
                                'route'               => 'intervenant/mise-en-paiement/edition',
                                'paramsInject'        => [
                                    'intervenant',
                                ],
                                'withtarget'          => true,
                                'resource'            => PrivilegeController::getResourceId('Application\Controller\Paiement', 'editionMiseEnPaiement'),
                                'visible'             => 'assertionPaiement',
                            ],
                        ],
                    ],
                    'gestion' => [
                        'pages' => [
                            'paiement'    => [
                                'label'        => "Paiement",
                                'title'        => "Paiement",
                                'route'        => 'paiement',
                                'icon'         => 'fa fa-credit-card',
                                'resource'     => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                                'pages'        => [
                                    'etat-demande-paiement' => [
                                        'label'    => "Mises en paiement",
                                        'title'    => "Mises en paiement",
                                        'route'    => 'paiement/etat-demande-paiement',
                                        'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                                    ],
                                    'etat-paiement'         => [
                                        'label'    => "État de paiement",
                                        'title'    => "État de paiement",
                                        'route'    => 'paiement/etat-paiement',
                                        'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION),
                                    ],
                                    'mises-en-paiement-csv' => [
                                        'label'    => "Mises en paiement (CSV)",
                                        'title'    => "Extraction des mises en paiement et demandes de mises en paiement au format tableur (CSV)",
                                        'route'    => 'paiement/mises-en-paiement-csv',
                                        'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_CSV),
                                    ],
                                    'extraction-winpaie'    => [
                                        'label'    => "Extraction Winpaie",
                                        'title'    => "Export des données de paiement au format Winpaie",
                                        'route'    => 'paiement/extraction-winpaie',
                                        'resource' => Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['demandeMiseEnPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
                    ],
                    'assertion'  => 'assertionPaiement',
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['visualisationMiseEnPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_DEMANDE,
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_INTERVENANT,
                    ],
                    'assertion'  => 'assertionPaiement',
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['editionMiseEnPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_EDITION,
                    ],
                    'assertion'  => 'assertionPaiement',
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['etatPaiement'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                    ],
                    'assertion'  => 'assertionPaiement',
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['miseEnPaiement'],
                    'privileges' => [Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION],
                    'assertion'  => 'assertionPaiement',
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['misesEnPaiementCsv'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_EXPORT_CSV,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['extractionWinpaie'],
                    'privileges' => [Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE],
                ],
            ],
        ],
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'MiseEnPaiement' => [],
                'TypeRessource'  => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => Privileges::MISE_EN_PAIEMENT_DEMANDE,
                        'resources'  => 'MiseEnPaiement',
                        'assertion'  => 'assertionPaiement',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationServiceAPayer'                      => Service\ServiceAPayer::class,
            'ApplicationMiseEnPaiement'                     => Service\MiseEnPaiement::class,
            'ApplicationMiseEnPaiementIntervenantStructure' => Service\MiseEnPaiementIntervenantStructure::class,
            'ApplicationTypeHeures'                         => Service\TypeHeures::class,
            'ApplicationCentreCout'                         => Service\CentreCout::class,
            'ApplicationCentreCoutEp'                       => Service\CentreCoutEp::class,
            'assertionPaiement'                             => Assertion\PaiementAssertion::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'DemandeMiseEnPaiement' => View\Helper\Paiement\DemandeMiseEnPaiementViewHelper::class,
            'TypeHeures'            => View\Helper\Paiement\TypeHeuresViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'PaiementMiseEnPaiementForm'          => Form\Paiement\MiseEnPaiementForm::class,
            'PaiementMiseEnPaiementRechercheForm' => Form\Paiement\MiseEnPaiementRechercheForm::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Paiement' => Controller\PaiementController::class,
        ],
    ],
];