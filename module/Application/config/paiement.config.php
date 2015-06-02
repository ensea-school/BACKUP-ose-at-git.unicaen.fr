<?php

namespace Application;

use Application\Entity\Db\Privilege;

return [
    'router' => [
        'routes' => [
            'paiement' => [
                'type'    => 'Literal',
                'may_terminate' => true,
                'options' => [
                    'route' => '/paiement',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Paiement',
                        'action' => 'index',
                    ],
                ],
                'child_routes' => [
                    'etat-demande-paiement' => [
                        'type'    => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/etat-demande-paiement',
                            'defaults' => [
                                'action' => 'etatPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::A_METTRE_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'mise-en-paiement' => [
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/mise-en-paiement/:structure/:intervenants',
                            'constraints' => [
                                'structure' => '[0-9]*'
                            ],
                            'defaults' => [
                                'action' => 'MiseEnPaiement',
                            ],
                        ],
                    ],
                    'etat-paiement' => [
                        'type'    => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/etat-paiement',
                            'defaults' => [
                                'action' => 'etatPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'mises-en-paiement-csv' => [
                        'type'    => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/mises-en-paiement-csv',
                            'defaults' => [
                                'action' => 'misesEnPaiementCsv'
                            ],
                        ],
                    ],
                    'extraction-winpaie' => [
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/extraction-winpaie[/:type][/:periode]',
                            'defaults' => [
                                'action' => 'extractionWinpaie'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'etat-demande-paiement' => [
                                'label'    => "Mises en paiement",
                                'title'    => "Mises en paiement",
                                'route'    => 'paiement/etat-demande-paiement',
                                'resource' => 'privilege/'.Privilege::MISE_EN_PAIEMENT_VISUALISATION,
                            ],
                            'etat-paiement' => [
                                'label'    => "État de paiement",
                                'title'    => "État de paiement",
                                'route'    => 'paiement/etat-paiement',
                                'resource' => 'privilege/'.Privilege::MISE_EN_PAIEMENT_VISUALISATION,
                            ],
                            'mises-en-paiement-csv' => [
                                'label'    => "Mises en paiement (CSV)",
                                'title'    => "Extraction des mises en paiement et demandes de mises en paiement au format tableur (CSV)",
                                'route'    => 'paiement/mises-en-paiement-csv',
                                'resource' => 'privilege/'.Privilege::MISE_EN_PAIEMENT_EXPORT_CSV,
                            ],
                            'extraction-winpaie' => [
                                'label'    => "Extraction Winpaie",
                                'title'    => "Export des données de paiement au format Winpaie",
                                'route'    => 'paiement/extraction-winpaie',
                                'resource' => 'privilege/'.Privilege::MISE_EN_PAIEMENT_EXPORT_PAIE,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['demandeMiseEnPaiement'],
                    'privileges' => [
                        Privilege::MISE_EN_PAIEMENT_DEMANDE
                    ],
                    'assertion'  => 'MiseEnPaiementAssertion',
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['etatPaiement'],
                    'privileges' => [
                        Privilege::MISE_EN_PAIEMENT_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['miseEnPaiement'],
                    'privileges' => [Privilege::MISE_EN_PAIEMENT_VISUALISATION]
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['misesEnPaiementCsv'],
                    'privileges' => [
                        Privilege::MISE_EN_PAIEMENT_EXPORT_CSV,
                    ]
                ],                
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['extractionWinpaie'],
                    'privileges' => [Privilege::MISE_EN_PAIEMENT_EXPORT_PAIE]
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'MiseEnPaiement' => [],
            ],
        ],
        'rule_providers' => [
            'Application\Provider\Rule\RuleProvider' => [
                'allow' => [
                    [
                        Privilege::MISE_EN_PAIEMENT_DEMANDE,
                        'MiseEnPaiement',
                        [Privilege::MISE_EN_PAIEMENT_DEMANDE],
                        'MiseEnPaiementAssertion',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationServiceAPayer'                      => 'Application\Service\ServiceAPayer',
            'ApplicationMiseEnPaiement'                     => 'Application\Service\MiseEnPaiement',
            'ApplicationMiseEnPaiementIntervenantStructure' => 'Application\Service\MiseEnPaiementIntervenantStructure',
            'ApplicationTypeHeures'                         => 'Application\Service\TypeHeures',
            'ApplicationCentreCout'                         => 'Application\Service\CentreCout',
            'MiseEnPaiementAssertion'                       => 'Application\Assertion\MiseEnPaiementAssertion',
            'MiseEnPaiementExisteRule'                      => 'Application\Rule\Paiement\MiseEnPaiementExisteRule',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'DemandeMiseEnPaiement'                 => 'Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper',
            'TypeHeures'                            => 'Application\View\Helper\Paiement\TypeHeuresViewHelper',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'PaiementMiseEnPaiementForm'            => 'Application\Form\Paiement\MiseEnPaiementForm',
            'PaiementMiseEnPaiementRechercheForm'   => 'Application\Form\Paiement\MiseEnPaiementRechercheForm',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Paiement'       => 'Application\Controller\PaiementController',
        ],
    ],
];