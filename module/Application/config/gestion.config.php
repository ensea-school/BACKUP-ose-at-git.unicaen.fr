<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'gestion'    => [
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
                        'controller'    => 'Discipline',
                        'action'        => 'index',
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
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Gestion', 'index'),
                        'order'    => 6,
                        'pages'    => [

                            'budget'   => ['border-color' => '#EB4995'],
                            'paiement' => ['border-color' => '#F5E79E'],

                            'agrement' => ['border-color' => '#E1AC5A'],

                            'indicateurs' => ['border-color' => '#217DD8'],
                            'pilotage'    => ['border-color' => '#00A020'],

                            'discipline' => [
                                'border-color' => '#9F491F',
                                'icon'         => 'glyphicon glyphicon-list-alt',
                                'label'        => "Disciplines",
                                'title'        => "Gestion des disciplines",
                                'route'        => 'discipline',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Discipline', 'index'),
                            ],
                            'dossier-pj' => ['border-color' => '#A22CAE'],
                            'droits'     => ['border-color' => '#E5272E'],
                            'parametres' => [ /* Emplacement réservé */],
                            'workflow'   => ['border-color' => '#111'],
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
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE,
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                        Privileges::DROIT_ROLE_VISUALISATION,
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                        Privileges::DROIT_AFFECTATION_VISUALISATION,
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
                        Privileges::PILOTAGE_VISUALISATION,
                        Privileges::BUDGET_VISUALISATION,
                        Privileges::DISCIPLINE_GESTION,
                        Privileges::INDICATEUR_VISUALISATION,
                    ],
                    'assertion'  => 'AssertionGestion',
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::DISCIPLINE_GESTION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['voir'],
                    'privileges' => [
                        Privileges::DISCIPLINE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Discipline',
                    'action'     => ['saisir', 'supprimer'],
                    'privileges' => [
                        Privileges::DISCIPLINE_EDITION,
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
            'AssertionGestion'      => Assertion\GestionAssertion::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'DisciplineForm' => Form\DisciplineForm::class,
        ],
    ],
];