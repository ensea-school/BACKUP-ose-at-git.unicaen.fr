<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'domaine-fonctionnel' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/domaine-fonctionnel',
                    'defaults' => [
                        'controller' => 'Application\Controller\DomaineFonctionnel',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:domaineFonctionnel',
                            'constraints' => [
                                'domaineFonctionnel' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:domaineFonctionnel]',
                            'constraints' => [
                                'domaineFonctionnel' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
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
                    'administration' => [
                        'pages' => [
                            'finances' => [
                                'pages' => [
                                    'domaine-fonctionnel' => [
                                        'label'        => 'Domaines fonctionnels',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'domaine-fonctionnel',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\DomaineFonctionnel', 'index'),
                                        'order'        => 80,
                                        'border-color' => '#BBCF55',
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
                    'controller' => 'Application\Controller\DomaineFonctionnel',
                    'action'     => ['index'],
                    'privileges' => Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\DomaineFonctionnel',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\DomaineFonctionnel' => Controller\DomaineFonctionnelController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\DomaineFonctionnelService::class => Service\DomaineFonctionnelService::class,
        ],
    ],
    'view_helpers'    => [
    ],
    'form_elements'   => [
        'invokables' => [
            Form\DomaineFonctionnel\DomaineFonctionnelSaisieForm::class => Form\DomaineFonctionnel\DomaineFonctionnelSaisieForm::class,
        ],
    ],
];
