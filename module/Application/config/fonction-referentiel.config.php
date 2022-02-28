<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'        => [
        'routes' => [
            'fonction-referentiel' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/fonction-referentiel',
                    'defaults' => [
                        'controller'    => 'Application\Controller\FonctionReferentiel',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:fonctionReferentiel]',
                            'constraints' => [
                                'fonctionReferentiel' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'delete' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/delete/:fonctionReferentiel',
                            'constraints' => [
                                'fonctionReferentiel' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],
    'navigation'    => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'fonction-referentiel' => [
                                'label'        => 'Référentiel fonctions',
                                'icon'         => 'fa-solid fa-graduation-cap',
                                'route'        => 'fonction-referentiel',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\FonctionReferentiel', 'index'),
                                'order'        => 80,
                                'border-color' => '#BBCF55',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'  => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\FonctionReferentiel',
                    'action'     => ['index'],
                    'privileges' => [Privileges::REFERENTIEL_ADMIN_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\FonctionReferentiel',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => [Privileges::REFERENTIEL_ADMIN_EDITION],
                ],
            ],
        ],
    ],
    'controllers'   => [
        'invokables' => [
            'Application\Controller\FonctionReferentiel' => Controller\FonctionReferentielController::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\FonctionReferentiel\FonctionReferentielSaisieForm::class => Form\FonctionReferentiel\FonctionReferentielSaisieForm::class,
        ],
    ],
];
