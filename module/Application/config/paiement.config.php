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
               /*'child_routes' => [
                    'saisie' => [
                        'type'    => 'Literal',
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/saisie',
                            'defaults' => [
                                'action' => 'miseEnPaiementSaisie',
                            ],
                        ],
                    ],
                ],*/
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['index','demandeMiseEnPaiement'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
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
            'ApplicationServiceAPayer'         => 'Application\Service\ServiceAPayer',
            'ApplicationMiseEnPaiement'        => 'Application\Service\MiseEnPaiement',
            'ApplicationTypeHeures'            => 'Application\Service\TypeHeures',
            'ApplicationCentreCout'            => 'Application\Service\CentreCout',
            'MiseEnPaiementAssertion'          => 'Application\\Assertion\\MiseEnPaiementAssertion',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'DemandeMiseEnPaiement'            => 'Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'MiseEnPaiementSaisie' => 'Application\Form\Paiement\MiseEnPaiementSaisieForm',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Paiement' => 'Application\Controller\PaiementController',
        ],
    ],
];