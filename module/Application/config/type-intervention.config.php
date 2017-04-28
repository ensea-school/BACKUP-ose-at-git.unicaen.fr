<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'        => [
        'routes' => [
            'type-intervention' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/type-intervention',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'TypeIntervention',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie' => [
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
                    'delete' => [
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
                ],
            ],
        ],
    ],
    'navigation'    => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'type-intervention' => [
                                'label'        => 'Type d\'intervention',
                                'title'        => 'Type d\'intervention',
                                'icon'         => 'fa  fa-commenting',
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
    'bjyauthorize'  => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\TypeIntervention',
                    'action'     => ['index'],
                    'privileges' => [Privileges::TYPE_INTERVENTION_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\TypeIntervention',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => [Privileges::TYPE_INTERVENTION_EDITION],
                ],
            ],
        ],
    ],
    'controllers'   => [
        'invokables' => [
            'Application\Controller\TypeIntervention' => Controller\TypeInterventionController::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'typeInterventionSaisie' => Form\TypeIntervention\TypeInterventionSaisieForm::class,
        ],
    ],
];
