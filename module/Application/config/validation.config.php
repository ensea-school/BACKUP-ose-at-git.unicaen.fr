<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantExterieurRole;

use Application\Entity\Db\Validation;

use Application\Assertion\OldAbstractAssertion;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'validation' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/validation',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Validation',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:validation',
                            'constraints' => [
                                'validation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action'     => 'voir',
                                'validation' => 0,
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:validation/supprimer/:typeVolumeHoraire',
                            'constraints' => [
                                'validation'        => '[0-9]*',
                                'typeVolumeHoraire' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'liste'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:typeValidation/:intervenant/liste',
                            'constraints' => [
                                'typeValidation' => '[0-9]*',
                                'intervenant'    => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action'         => 'liste',
                                'typeValidation' => 0,
                                'intervenant'    => 0,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'validation' => [
                        'label'    => 'Validation',
                        'route'    => 'validation/liste',
                        'visible'  => false,
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Validation', 'liste'),
                        'pages'    => [
                            'voir' => [
                                'label'      => "Détails",
                                'title'      => "Détails d'une validation",
                                'route'      => 'validation/voir',
                                'withtarget' => true,
                                'resource'   => PrivilegeController::getResourceId('Application\Controller\Validation', 'voir'),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['index', 'liste', 'voir'],
                    'roles'      => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['dossier'],
                    'roles'      => [IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['service', 'referentiel'],
                    'roles'      => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['referentiel'],
                    'roles'      => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Validation',
                    'action'     => ['supprimer'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO => [],
                Validation::RESOURCE_ID_CLOTURE_REALISE          => [],
                Validation::RESOURCE_ID_VALIDATION_ENSEIGNEMENT  => [],
                Validation::RESOURCE_ID_VALIDATION_REFERENTIEL   => [],
            ],
        ],
        'rule_providers'     => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        [
                            IntervenantRole::ROLE_ID,
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        [
                            Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO,
                            Validation::RESOURCE_ID_CLOTURE_REALISE,
                            Validation::RESOURCE_ID_VALIDATION_ENSEIGNEMENT,
                            Validation::RESOURCE_ID_VALIDATION_REFERENTIEL,
                        ],
                        [
                            OldAbstractAssertion::PRIVILEGE_READ,
                        ],
                        'ValidationAssertion',
                    ],

                    // ------------- Validation DONNEES PERSO -------------
                    [
                        [
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        [
                            Validation::RESOURCE_ID_VALIDATION_DONNEES_PERSO,
                        ],
                        [
                            OldAbstractAssertion::PRIVILEGE_CREATE,
                            OldAbstractAssertion::PRIVILEGE_DELETE,
                        ],
                        'ValidationAssertion',
                    ],

                    // ------------- Cloture REALISE -------------
                    [
                        [
                            IntervenantRole::ROLE_ID, // <-- Hey!
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        [
                            Validation::RESOURCE_ID_CLOTURE_REALISE,
                        ],
                        [
                            OldAbstractAssertion::PRIVILEGE_CREATE,
                        ],
                        'ValidationAssertion',
                    ],

                    // ------------- Validation ENSEIGNEMENT et REFERENTIEL -------------
                    [
                        [
                            ComposanteRole::ROLE_ID,
                            AdministrateurRole::ROLE_ID,
                        ],
                        [
                            Validation::RESOURCE_ID_VALIDATION_ENSEIGNEMENT,
                            Validation::RESOURCE_ID_VALIDATION_REFERENTIEL,
                        ],
                        [
                            OldAbstractAssertion::PRIVILEGE_CREATE,
                            OldAbstractAssertion::PRIVILEGE_DELETE,
                        ],
                        'ValidationAssertion',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Validation' => Controller\ValidationController::class,
        ],
    ],
    'service_manager' => [
        'invokables'   => [
            'ApplicationTypeValidation'      => Service\TypeValidation::class,
            'ApplicationValidation'          => Service\Validation::class,
            'ValidationEnseignementRule'     => Rule\Validation\Enseignement\ValidationRule::class,
            'ValidationReferentielRule'      => Rule\Validation\Referentiel\ValidationRule::class,
            'ClotureRealiseRule'             => Rule\Validation\ClotureRealiseRule::class,
            'ValidationAssertion'            => Assertion\ValidationAssertionProxy::class,
            'ValidationDossierAssertion'     => Assertion\ValidationDossierAssertion::class,
            'ValidationServiceAssertion'     => Assertion\ValidationServiceAssertion::class,
            'ValidationReferentielAssertion' => Assertion\ValidationReferentielAssertion::class,
            'ClotureRealiseAssertion'        => Assertion\ClotureRealiseAssertion::class,
        ],
        'factories'    => [
            'ValidationEnseignementPrevuRule'   => Rule\Validation\Enseignement\Prevu\RuleFactory::class,
            'ValidationEnseignementRealiseRule' => Rule\Validation\Enseignement\Realise\RuleFactory::class,
            'ValidationReferentielPrevuRule'    => Rule\Validation\Referentiel\Prevu\RuleFactory::class,
            'ValidationReferentielRealiseRule'  => Rule\Validation\Referentiel\Realise\RuleFactory::class,
        ],
        'initializers' => [
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
        ],
    ],
    'form_elements'   => [
        'invokables' => [
        ],
    ],
];
