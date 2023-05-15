<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'centre-cout-activite' => [
            'route'      => '/centre-cout-activite',
            'controller' => Controller\CentreCoutController::class,
            'action'     => 'centre-cout-activite',
            'may_terminate' => true,
            'child_routes'  => [
                'delete' => [
                    'route'       => '/delete/:ccActivite',
                    'action'      => 'centre-cout-activite-delete',
                    'constraints' => [
                        'ccActivite' => '[0-9]*',
                    ],
                ],
                'saisie' => [
                    'options' => [
                        'route'       => '/saisie/[:ccActivite]',
                        'action'      => 'centre-cout-activite-saisie',
                        'constraints' => [
                            'ccActivite' => '[0-9]*',
                        ],
                    ],
                ],
            ],
        ],

        'centre-cout' => [
            'route'         => '/centre-cout',
            'controller'    => Controller\CentreCoutController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'delete'           => [
                    'route'       => '/delete/:centreCout',
                    'action'      => 'delete',
                    'constraints' => [
                        'centreCout' => '[0-9]*',
                    ],
                ],
                'saisie'           => [
                    'route'       => '/saisie/[:centreCout]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'centreCout' => '[0-9]*',
                    ],
                ],
                'delete-structure' => [
                    'route'       => '/delete-structure/:centreCoutStructure',
                    'action'      => 'delete-structure',
                    'constraints' => [
                        'centreCoutStructure' => '[0-9]*',
                    ],
                ],
                'saisie-structure' => [
                    'route'       => '/saisie-structure/:centreCout/[:centreCoutStructure]',
                    'action'      => 'saisie-structure',
                    'constraints' => [
                        'centreCout'          => '[0-9]*',
                        'centreCoutStructure' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'finances' => [
                    'pages' => [
                        'centre-cout'          => [
                            'label'    => 'Centres de coûts',
                            'route'    => 'centre-cout',
                            'resource' => PrivilegeController::getResourceId(Controller\CentreCoutController::class, 'index'),
                            'order'    => 10,
                            'color'    => '#BBCF55',
                        ],
                        'centre-cout-activite' => [
                            'label'    => 'Types d\'activités des centres de coûts',
                            'route'    => 'centre-cout-activite',
                            'resource' => PrivilegeController::getResourceId(Controller\CentreCoutController::class, 'index'),
                            'order'    => 40,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\CentreCoutController::class,
            'action'     => ['index', 'centre-cout-activite'],
            'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
        ],
        [
            'controller' => Controller\CentreCoutController::class,
            'action'     => ['saisie', 'delete', 'saisie-structure', 'delete-structure', 'centre-cout-activite-delete', 'centre-cout-activite-saisie'],
            'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION,
        ],
    ],

    'controllers' => [
        'invokables' => [
            Controller\CentreCoutController::class => Controller\CentreCoutController::class,
        ],
    ],

    'services' => [
        'invokables' => [
            \Paiement\Service\CentreCoutService::class          => \Paiement\Service\CentreCoutService::class,
            \Paiement\Service\CentreCoutStructureService::class => \Paiement\Service\CentreCoutStructureService::class,
            \Paiement\Service\CcActiviteService::class          => \Paiement\Service\CcActiviteService::class,
        ],
    ],

    'forms' => [
        'invokables' => [
            \Paiement\Form\CentreCout\CentreCoutSaisieForm::class         => \Paiement\Form\CentreCout\CentreCoutSaisieForm::class,
            Form\CentreCout\CentreCoutStructureSaisieForm::class          => Form\CentreCout\CentreCoutStructureSaisieForm::class,
            \Paiement\Form\CentreCout\CentreCoutActiviteSaisieForm::class => \Paiement\Form\CentreCout\CentreCoutActiviteSaisieForm::class,
        ],
    ],
];
