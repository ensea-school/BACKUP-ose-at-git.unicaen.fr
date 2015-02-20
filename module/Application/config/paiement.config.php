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
                    'mise-en-paiement' => [
                        'type'    => 'Literal',
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/mise-en-paiement',
                            'defaults' => [
                                'action' => 'miseEnPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::A_METTRE_EN_PAIEMENT,
                            ],
                        ],
                    ],
                    'etat-paiement' => [
                        'type'    => 'Literal',
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/etat-paiement',
                            'defaults' => [
                                'action' => 'miseEnPaiement',
                                'etat'   => Entity\Db\MiseEnPaiement::MIS_EN_PAIEMENT,
                            ],
                        ],
                    ],
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
                            'mise-en-paiement' => array(
                                'label'    => "Mise en paiement",
                                'title'    => "Mise en paiement",
                                'route'    => 'paiement/mise-en-paiement',
                            ),
                            'etat-paiement' => array(
                                'label'    => "État de paiement",
                                'title'    => "État de paiement",
                                'route'    => 'paiement/etat-paiement',
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
                    'action'     => ['index','demandeMiseEnPaiement','miseEnPaiement'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR, R_DRH],
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
            'DemandeMiseEnPaiement'            => 'Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'PaiementMiseEnPaiementRechercheForm'             => 'Application\Form\Paiement\MiseEnPaiementRechercheForm',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Paiement' => 'Application\Controller\PaiementController',
        ],
    ],
];