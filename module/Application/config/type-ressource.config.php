<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'type-ressource' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/type-ressource',
                    'defaults' => [
                        'controller' => 'Application\Controller\TypeRessource',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'                             => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:typeRessource]',
                            'constraints' => [
                                'typeIntervention' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'delete'                             => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/delete[/:typeRessource]',
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
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'type-ressource' => [
                                'label'        => 'Types de ressources',
                                'icon'         => 'fa  fa-commenting',
                                'route'        => 'type-ressource',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\TypeRessource', 'index'),
                                'order'        => 60,
                                'border-color' => '#71DFD7',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'TypeRessource' => [],
            ],
        ],
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\TypeRessource',
                    'action'     => ['index'],
                    'privileges' => [Privileges::TYPE_RESSOURCE_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\TypeRessource',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => [Privileges::TYPE_RESSOURCE_EDITION],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\TypeRessourceService::class => Service\TypeRessourceService::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\TypeRessource' => Controller\TypeRessourceController::class,
        ],
    ],
    /*'form_elements'   => [
        'invokables' => [
            Form\TypeIntervention\TypeInterventionSaisieForm::class          => Form\TypeIntervention\TypeInterventionSaisieForm::class,
            Form\TypeIntervention\TypeInterventionStructureSaisieForm::class => Form\TypeIntervention\TypeInterventionStructureSaisieForm::class,
            Form\TypeIntervention\TypeInterventionStatutSaisieForm::class => Form\TypeIntervention\TypeInterventionStatutSaisieForm::class,
            Form\TypeIntervention\TypeInterventionStatutDeleteForm::class => Form\TypeIntervention\TypeInterventionStatutDeleteForm::class,
        ],
    ],*/
    /*'view_helpers'    => [
        'invokables' => [
            'typeInterventionAdmin' => View\Helper\TypeInterventionAdminViewHelper::class,
        ],
    ],*/
];
