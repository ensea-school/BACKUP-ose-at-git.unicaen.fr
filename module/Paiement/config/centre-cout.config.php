<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'centre-cout-activite' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/centre-cout-activite',
                    'defaults' => [
                        'controller' => 'Application\Controller\CentreCout',
                        'action'     => 'centre-cout-activite',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:ccActivite',
                            'constraints' => [
                                'ccActivite' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'centre-cout-activite-delete',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:ccActivite]',
                            'constraints' => [
                                'ccActivite' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'centre-cout-activite-saisie',
                            ],
                        ],
                    ],
                ],
            ],

            'centre-cout' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/centre-cout',
                    'defaults' => [
                        'controller' => 'Application\Controller\CentreCout',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:centreCout',
                            'constraints' => [
                                'centreCout' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'saisie'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:centreCout]',
                            'constraints' => [
                                'centreCout' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'delete-structure' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete-structure/:centreCoutStructure',
                            'constraints' => [
                                'centreCoutStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete-structure',
                            ],
                        ],
                    ],
                    'saisie-structure' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie-structure/:centreCout/[:centreCoutStructure]',
                            'constraints' => [
                                'centreCout'          => '[0-9]*',
                                'centreCoutStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie-structure',
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
                    'administration' => [
                        'pages' => [
                            'finances' => [
                                'pages' => [
                                    'centre-cout'          => [
                                        'label'        => 'Centres de coûts',
                                        'route'        => 'centre-cout',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\CentreCout', 'index'),
                                        'order'        => 10,
                                        'color' => '#BBCF55',
                                    ],
                                    'centre-cout-activite' => [
                                        'label'        => 'Types d\'activités des centres de coûts',
                                        'route'        => 'centre-cout-activite',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\CentreCout', 'index'),
                                        'order'        => 40,
                                        'color' => '#BBCF55',
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
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\CentreCout',
                    'action'     => ['index', 'centre-cout-activite'],
                    'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\CentreCout',
                    'action'     => ['saisie', 'delete', 'saisie-structure', 'delete-structure', 'centre-cout-activite-delete', 'centre-cout-activite-saisie'],
                    'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\CentreCout' => \Paiement\Controller\CentreCoutController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            \Paiement\Service\CentreCoutService::class          => \Paiement\Service\CentreCoutService::class,
            \Paiement\Service\CentreCoutStructureService::class => \Paiement\Service\CentreCoutStructureService::class,
            \Paiement\Service\CcActiviteService::class          => \Paiement\Service\CcActiviteService::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            \Paiement\Form\CentreCout\CentreCoutSaisieForm::class         => \Paiement\Form\CentreCout\CentreCoutSaisieForm::class,
            Form\CentreCout\CentreCoutStructureSaisieForm::class          => Form\CentreCout\CentreCoutStructureSaisieForm::class,
            \Paiement\Form\CentreCout\CentreCoutActiviteSaisieForm::class => \Paiement\Form\CentreCout\CentreCoutActiviteSaisieForm::class,
        ],
    ],
];
