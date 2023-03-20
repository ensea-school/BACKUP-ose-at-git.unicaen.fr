<?php

namespace Application;

use UnicaenPrivilege\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'router'       => [
        'routes' => [
            'etablissement' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/etablissement',
                    'defaults' => [
                        'controller' => 'Application\Controller\Etablissement',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'modifier'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'recherche' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/recherche[/:term]',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/saisie[/:etablissement]',
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/supprimer/:etablissement',
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Etablissement',
                    'action'     => ['choisir', 'recherche', 'voir', 'apercevoir',],
                    'roles'      => ['user']],
            ],
            PrivilegeController::class      => [
                [
                    'controller' => 'Application\Controller\Etablissement',
                    'action'     => ['index'],
                    'privileges' => [Privileges::PARAMETRES_ETABLISSEMENT_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Etablissement',
                    'action'     => ['saisie', 'supprimer'],
                    'privileges' => [Privileges::PARAMETRES_ETABLISSEMENT_EDITION],
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
                            'nomenclatures' => [
                                'pages' => [
                                    'gestion-etablissement' => [
                                        'label'          => 'Établissements',
                                        'route'          => 'etablissement',
                                        'resource'       => PrivilegeController::getResourceId('Application\Controller\Etablissement', 'index'),
                                        'order'          => 20,
                                        'border - color' => '#111',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers'     => [
        'factories' => [
            'Application\Controller\Etablissement' => Controller\Factory\EtablissementControllerFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\Etablissement\EtablissementSaisieForm::class => Form\Etablissement\EtablissementSaisieFormFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\EtablissementService::class => Service\EtablissementService::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'etablissement' => View\Helper\EtablissementViewHelper::class,
        ],
    ],
];
