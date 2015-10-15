<?php

namespace Application;

use Application\Entity\Db\Privilege;

return [
    'router'          => [
        'routes' => [
            'gestion' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/gestion',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Gestion',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
            'discipline' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/discipline',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Discipline',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir'      => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/voir/:discipline',
                            'constraints' => [
                                'discipline' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'voir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'saisir'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisir[/:discipline]',
                            'constraints' => [
                                'discipline' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:discipline',
                            'constraints' => [
                                'discipline' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
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
                    'gestion' => [
                        'label'    => "Gestion",
                        'route'    => 'gestion',
                        'resource' => 'controller/Application\Controller\Gestion:index',
                        'pages' => [
                            'discipline' => [
                                'label'    => "Disciplines",
                                'title'    => "Gestion des disciplines",
                                'route'    => 'discipline',
                                'resource' => 'privilege/'.Privilege::DISCIPLINE_GESTION
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            Guard\PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['index'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                    'privileges' => [
                        Privilege::MISE_EN_PAIEMENT_EXPORT_PAIE,
                        Privilege::MISE_EN_PAIEMENT_VISUALISATION,
                        Privilege::DROIT_ROLE_VISUALISATION,
                        Privilege::DROIT_PRIVILEGE_VISUALISATION,
                        Privilege::DROIT_AFFECTATION_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['index'],
                    'privileges' => [
                        Privilege::DISCIPLINE_GESTION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['voir'],
                    'privileges' => [
                        Privilege::DISCIPLINE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['saisir','supprimer'],
                    'privileges' => [
                        Privilege::DISCIPLINE_EDITION,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Gestion'    => Controller\GestionController::class,
            'Application\Controller\Discipline' => Controller\DisciplineController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationDiscipline' => Service\DisciplineService::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'DisciplineForm'       => Form\DisciplineForm::class,
        ],
    ],
];