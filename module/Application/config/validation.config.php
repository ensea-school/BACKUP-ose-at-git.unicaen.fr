<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'intervenant' => [
                'child_routes' => [
                    'validation' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/:intervenant/validation',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'service'     => [
                                'type'          => 'Literal',
                                'options'       => [
                                    'route'    => '/service',
                                    'defaults' => [
                                        'controller' => 'Service',
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
                                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                                            ],
                                        ],
                                    ],
                                    'realise'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/realise',
                                            'defaults' => [
                                                'action'                   => 'validation',
                                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
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
                            'referentiel' => [
                                'type'          => 'Literal',
                                'options'       => [
                                    'route'    => '/referentiel',
                                    'defaults' => [
                                        'controller' => 'ServiceReferentiel',
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
                                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_PREVU,
                                            ],
                                        ],
                                    ],
                                    'realise'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/realise',
                                            'defaults' => [
                                                'action'                   => 'validation',
                                                'type-volume-horaire-code' => Entity\Db\TypeVolumeHoraire::CODE_REALISE,
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
                            'validation-service-prevu'       => [
                                'label'        => "Validation des enseignements prévisionnels",
                                'title'        => "Validation des enseignements prévisionnels de l'intervenant",
                                'route'        => 'intervenant/validation/service/prevu',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Service', 'validation'),
                            ],
                            'validation-referentiel-prevu'   => [
                                'label'        => "Validation du référentiel prévisionnel",
                                'title'        => "Validation du référentiel prévisionnel de l'intervenant",
                                'route'        => 'intervenant/validation/referentiel/prevu',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\ServiceReferentiel', 'validation'),
                            ],
                            'validation-service-realise'     => [
                                'label'        => "Validation des enseignements réalisés",
                                'title'        => "Validation des enseignements réalisés de l'intervenant",
                                'route'        => 'intervenant/validation/service/realise',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Service', 'validation'),
                            ],
                            'validation-referentiel-realise' => [
                                'label'        => "Validation du référentiel réalisé",
                                'title'        => "Validation du référentiel réalisé de l'intervenant",
                                'route'        => 'intervenant/validation/referentiel/realise',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\ServiceReferentiel', 'validation'),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers'  => [
        'invokables' => [
            'Application\Controller\Validation' => Controller\ValidationController::class,
        ],
    ],
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Validation' => [],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'ApplicationTypeValidation'       => Service\TypeValidation::class,
            'ApplicationValidation'           => Service\Validation::class,
            'ValidationEnseignementRule'      => Rule\Validation\Enseignement\ValidationRule::class,
            'ValidationReferentielRule'       => Rule\Validation\Referentiel\ValidationRule::class,
            'ValidationAssertion'             => Assertion\ValidationAssertionProxy::class,
            'ValidationServiceAssertion'      => Assertion\ValidationServiceAssertion::class,
            'ValidationReferentielAssertion'  => Assertion\ValidationReferentielAssertion::class,
            'processusValidationEnseignement' => Processus\ValidationEnseignementProcessus::class,
            'processusValidationReferentiel'  => Processus\ValidationReferentielProcessus::class,
        ],
        'factories'  => [
            'ValidationEnseignementPrevuRule'   => Rule\Validation\Enseignement\Prevu\RuleFactory::class,
            'ValidationEnseignementRealiseRule' => Rule\Validation\Enseignement\Realise\RuleFactory::class,
            'ValidationReferentielPrevuRule'    => Rule\Validation\Referentiel\Prevu\RuleFactory::class,
            'ValidationReferentielRealiseRule'  => Rule\Validation\Referentiel\Realise\RuleFactory::class,
        ],
    ],
];
