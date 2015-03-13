<?php

namespace Application;

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
                    ]
                ],
            ],
        ],
    ],
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'gestion' => array(
                        'pages' => array(
                            'etat-demande-paiement' => array(
                                'label'    => "Mise en paiement",
                                'title'    => "Mise en paiement",
                                'route'    => 'paiement/etat-demande-paiement',
                            ),
                            'etat-paiement' => array(
                                'label'    => "État de paiement",
                                'title'    => "État de paiement",
                                'route'    => 'paiement/etat-paiement',
                            ),
                            'mises-en-paiement-csv' => array(
                                'label'    => "Mises en paiement (CSV)",
                                'title'    => "Extraction des mises en paiement et demandes de mises en paiement au format tableur (CSV)",
                                'route'    => 'paiement/mises-en-paiement-csv',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['index','demandeMiseEnPaiement','etatPaiement','misesEnPaiementCsv'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR, R_DRH],
                ],
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['miseEnPaiement'],
                    'roles'      => [R_ADMINISTRATEUR, R_DRH],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'MiseEnPaiement' => [],
            ],
        ],
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        [R_ROLE],
                        'MiseEnPaiement',
                        [
                            Assertion\MiseEnPaiementAssertion::PRIVILEGE_VISUALISATION,
                            Assertion\MiseEnPaiementAssertion::PRIVILEGE_DEMANDE,
                            Assertion\MiseEnPaiementAssertion::PRIVILEGE_VALIDATION,
                            Assertion\MiseEnPaiementAssertion::PRIVILEGE_MISE_EN_PAIEMENT,
                        ],
                        Assertion\MiseEnPaiementAssertion::getAssertionId(),
                    ],
                    [
                        [R_ADMINISTRATEUR, R_DRH],
                        'MiseEnPaiement',
                        ['export-csv-winpaie'],
                        
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