<?php

namespace Application;

use Application\Entity\Db\WfEtape;

return [
    'router' => [
        'routes' => [
            'intervenant' => [
                'child_routes' => [
                    'validation' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route' => '/:intervenant/validation',
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'service' => [
                                'type'          => 'Literal',
                                'options'       => [
                                    'route'    => '/service',
                                    'defaults' => [
                                        'controller' => 'Application\Controller\Service',
                                    ],
                                ],
                                'may_terminate' => false,
                                'child_routes'  => [
                                    'prevu'     => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/prevu',
                                            'defaults' => [
                                                'action'                   => 'validation',
                                                'type-volume-horaire-code' => 'PREVU',
                                            ],
                                        ],
                                    ],
                                    'realise'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/realise',
                                            'defaults' => [
                                                'action'                   => 'validation',
                                                'type-volume-horaire-code' => 'REALISE',
                                            ],
                                        ],
                                    ],
                                    'valider'   => [
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'       => '/valider/:typeVolumeHoraire/:structure',
                                            'constraints' => [
                                                'typeVolumeHoraire' => '[0-9]*',
                                                'structure'         => '[0-9]*',
                                            ],
                                            'defaults'    => [
                                                'action' => 'valider',
                                            ],
                                        ],
                                    ],
                                    'devalider' => [
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'       => '/devalider/:validation',
                                            'constraints' => [
                                                'validation' => '[0-9]*',
                                            ],
                                            'defaults'    => [
                                                'action' => 'devalider',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'intervenant' => [
                        'pages' => [
                            'validation-service-prevu'   => [
                                'label'               => "Validation des enseignements prévisionnels",
                                'title'               => "Validation des enseignements prévisionnels de l'intervenant",
                                'route'               => 'intervenant/validation/service/prevu',
                                'paramsInject'        => [
                                    'intervenant',
                                ],
                                'workflow-etape-code' => WfEtape::CODE_SERVICE_VALIDATION,
                                'withtarget'          => true,
                                'visible'             => Assertion\ServiceAssertion::class,
                                'order'               => 8,
                            ],
                            'validation-service-realise' => [
                                'label'               => "Validation des enseignements réalisés",
                                'title'               => "Validation des enseignements réalisés de l'intervenant",
                                'route'               => 'intervenant/validation/service/realise',
                                'paramsInject'        => [
                                    'intervenant',
                                ],
                                'workflow-etape-code' => WfEtape::CODE_SERVICE_VALIDATION_REALISE,
                                'withtarget'          => true,
                                'visible'             => Assertion\ServiceAssertion::class,
                                'order'               => 14,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            Service\TypeValidationService::class             => Service\TypeValidationService::class,
            Service\ValidationService::class                 => Service\ValidationService::class,
            Processus\ValidationEnseignementProcessus::class => Processus\ValidationEnseignementProcessus::class,
        ],
    ],
];
