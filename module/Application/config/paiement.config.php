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
                    'centre-de-cout' => [
                        'type'    => 'Literal',
                        'may_terminate' => false,
                        'options' => [
                            'route'    => '/centre-de-cout',
                        ],
                        'child_routes' => [
                            'recherche' => [
                                'type'    => 'Literal',
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/recherche',
                                    'defaults' => [
                                        'action' => 'centreCoutRecherche',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['index','miseEnPaiement','miseEnPaiementSaisie','centreCoutRecherche'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationTypeHeures'            => 'Application\Service\TypeHeures',
            'ApplicationCentreCout'            => 'Application\Service\CentreCout',
            'FormMiseEnPaiementSaisieHydrator' => 'Application\Form\Paiement\MiseEnPaiementSaisieHydrator',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'PaiementLigne'             => 'Application\View\Helper\Paiement\LigneViewHelper',
            'MiseEnPaiementSaisieForm'  => 'Application\View\Helper\Paiement\MiseEnPaiementSaisieFormViewHelper'
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