<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'type-formation' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/type-formation',
                    'defaults' => [
                        'controller' => 'Application\Controller\TypeFormation',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:typeFormation][/:groupeTypeFormation]',
                            'constraints' => [
                                'typeFormation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'ajout'           => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/ajout/:groupeTypeFormation',
                            'constraints' => [
                                'typeFormation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'ajout',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer'        => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:typeFormation',
                            'constraints' => [
                                'typeFormation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'saisie-groupe'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie-groupe[/:groupeTypeFormation]',
                            'constraints' => [
                                'groupeTypeFormation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisieGroupe',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer-groupe' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer-groupe/:groupeTypeFormation',
                            'constraints' => [
                                'groupeTypeFormation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimerGroupe',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'trier'            => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/trier/',
                            'constraints' => [
                            ],
                            'defaults'    => [
                                'action' => 'trier',
                            ],
                        ],
                        'may_terminate' => true,
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
                            'odf' => [
                                'pages' => [
                                    'gestion-type-formation' => [
                                        'label'          => 'Types de formations',
                                        'route'          => 'type-formation',
                                        'resource'       => PrivilegeController::getResourceId('Application\Controller\TypeFormation', 'index'),
                                        'order'          => 60,
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

    'bjyauthorize'  => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\TypeFormation',
                    'action'     => ['index'],
                    'privileges' => [Privileges::ODF_TYPE_FORMATION_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\TypeFormation',
                    'action'     => ['saisie', 'supprimer', 'saisieGroupe', 'supprimerGroupe', "trier", 'ajout'],
                    'privileges' => [Privileges::ODF_TYPE_FORMATION_EDITION],
                ],
            ],
        ],
    ],
    'controllers'   => [
        'factories' => [
            'Application\Controller\TypeFormation'       => Controller\Factory\TypeFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm::class => Form\GroupeTypeFormation\GroupeTypeFormationSaisieFormFactory::class,
            Form\TypeFormation\TypeFormationSaisieForm::class             => Form\TypeFormation\TypeFormationSaisieFormFactory::class,
        ],
    ],
];
