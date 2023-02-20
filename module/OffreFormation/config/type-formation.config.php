<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace OffreFormation;


use Application\Provider\Privilege\Privileges;
use OffreFormation\Controller\TypeFormationController;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'type-formation' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/type-formation',
                    'defaults' => [
                        'controller' => TypeFormationController::class,
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
                                        'resource'       => PrivilegeController::getResourceId(TypeFormationController::class, 'index'),
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
                    'controller' => TypeFormationController::class,
                    'action'     => ['index'],
                    'privileges' => [Privileges::ODF_TYPE_FORMATION_VISUALISATION],
                ],
                [
                    'controller' => TypeFormationController::class,
                    'action'     => ['saisie', 'supprimer', 'saisieGroupe', 'supprimerGroupe', "trier"],
                    'privileges' => [Privileges::ODF_TYPE_FORMATION_EDITION],
                ],
            ],
        ],
    ],
    'controllers'   => [
        'factories' => [
            TypeFormationController::class       => Controller\Factory\TypeFormationControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\TypeFormation\TypeFormationSaisieForm::class => Form\TypeFormation\TypeFormationSaisieFormFactory::class,
        ],
    ],
];
