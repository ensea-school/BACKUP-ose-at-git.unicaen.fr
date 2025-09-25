<?php

namespace Intervenant;


use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'grades' => [
            'route'         => '/grades',
            'controller'    => Controller\GradeController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::NOMENCLATURE_RH_GRADES_VISUALISATION],
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'route'       => '/saisie[/:grade]',
                    'constraints' => [
                        'grade' => '[0-9]*',
                    ],
                    'controller'  => Controller\GradeController::class,
                    'action'      => 'saisie',
                    'privileges'  => [Privileges::NOMENCLATURE_RH_GRADES_EDITION],
                ],
                'delete' => [
                    'route'       => '/delete/:grade',
                    'constraints' => [
                        'grade' => '[0-9]*',
                    ],
                    'controller'  => Controller\GradeController::class,
                    'action'      => 'delete',
                    'privileges'  => [Privileges::NOMENCLATURE_RH_GRADES_EDITION],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'rh' => [
                    'pages' => [
                        'grade' => [
                            'label'    => 'Grades',
                            'route'    => 'grades',
                            'resource' => Authorize::controllerResource(Controller\GradeController::class, 'index'),
                            'order'    => 30,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\GradeController::class => Controller\GradeControllerFactory::class,
    ],
    'forms'       => [
        Form\GradeSaisieForm::class => Form\GradeSaisieFormFactory::class,
    ],
    'services' => [
        Service\GradeService::class    => Service\GradeServiceFactory::class,
    ],
];
