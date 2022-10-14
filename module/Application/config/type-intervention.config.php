<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'type-intervention' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/type-intervention',
                    'defaults' => [
                        'controller' => 'Application\Controller\TypeIntervention',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'                             => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:typeIntervention]',
                            'constraints' => [
                                'typeIntervention' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'statut'                             => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/statut/:typeIntervention',
                            'constraints' => [
                                'typeIntervention' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'statut',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'delete'                             => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/delete/:typeIntervention',
                            'constraints' => [
                                'typeIntervention' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-intervention-trier'            => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'      => '/type-intervention-trier',
                            'contraints' => [
                            ],
                            'defaults'   => [
                                'action' => 'type-intervention-trier',
                            ],
                        ],
                        'may_terminate' => 'true',
                    ],
                    'type-intervention-structure-saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-intervention-structure-saisie/:typeIntervention[/:typeInterventionStructure]',
                            'constraints' => [
                                'typeIntervention'          => '[0-9]*',
                                'typeInterventionStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-intervention-structure-saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'type-intervention-structure-delete' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/type-intervention-structure-delete/:typeInterventionStructure',
                            'constraints' => [
                                'typeInterventionStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-intervention-structure-delete',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'statut-saisie'                      => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/statut-saisie/:typeIntervention[/:typeInterventionStatut]',
                            'constraints' => [
                                'typeIntervention'       => '[0-9]*',
                                'typeInterventionStatut' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'statut-saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'statut-delete'                      => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/statut-delete/:typeIntervention/:typeInterventionStatut',
                            'constraints' => [
                                'typeInterventionStatut' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'statut-delete',
                            ],
                        ],
                        'may_terminate' => true,
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
                            'odf' => [
                                'pages' => [
                                    'type-intervention' => [
                                        'label'        => 'Types d\'interventions',
                                        'icon'         => 'fas fa-commenting',
                                        'route'        => 'type-intervention',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\TypeIntervention', 'index'),
                                        'order'        => 60,
                                        'border-color' => '#71DFD7',
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
                    'controller' => 'Application\Controller\TypeIntervention',
                    'action'     => ['index', 'statut'],
                    'privileges' => [Privileges::TYPE_INTERVENTION_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\TypeIntervention',
                    'action'     => ['saisie', 'delete', 'type-intervention-structure-saisie', 'type-intervention-structure-delete',
                                     'type-intervention-trier', 'statut-saisie', 'statut-delete'],
                    'privileges' => [Privileges::TYPE_INTERVENTION_EDITION],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\TypeInterventionStructureService::class => Service\TypeInterventionStructureService::class,
            Service\TypeInterventionStatutService::class    => Service\TypeInterventionStatutService::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\TypeIntervention' => Controller\TypeInterventionController::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\TypeIntervention\TypeInterventionSaisieForm::class          => Form\TypeIntervention\TypeInterventionSaisieForm::class,
            Form\TypeIntervention\TypeInterventionStructureSaisieForm::class => Form\TypeIntervention\TypeInterventionStructureSaisieForm::class,
            Form\TypeIntervention\TypeInterventionStatutSaisieForm::class    => Form\TypeIntervention\TypeInterventionStatutSaisieForm::class,
            Form\TypeIntervention\TypeInterventionStatutDeleteForm::class    => Form\TypeIntervention\TypeInterventionStatutDeleteForm::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'typeInterventionAdmin' => View\Helper\TypeInterventionAdminViewHelper::class,
        ],
    ],
];
