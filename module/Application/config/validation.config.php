<?php

namespace Application;

use Application\Acl\Role;
use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DirecteurComposanteRole;
use Application\Acl\GestionnaireComposanteRole;
use Application\Acl\ResponsableComposanteRole;
use Application\Acl\SuperviseurComposanteRole;
use Application\Acl\ResponsableRechercheLaboRole;
use Application\Acl\DrhRole;
use Application\Acl\GestionnaireDrhRole;
use Application\Acl\ResponsableDrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\SuperviseurEtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\FoadRole;
use Application\Acl\ResponsableFoadRole;

use Application\Assertion\AbstractAssertion;

return [
    'router' => [
        'routes' => [
            'validation' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/validation',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Validation',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'voir' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:validation',
                            'constraints' => [
                                'validation' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'voir',
                                'validation' => 0,
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:validation/supprimer',
                            'constraints' => [
                                'validation' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'liste' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:typeValidation/:intervenant/liste',
                            'constraints' => [
                                'typeValidation' => '[0-9]*',
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'liste',
                                'typeValidation' => 0,
                                'intervenant' => 0,
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
                    'validation' => [
                        'label'    => 'Validation',
                        'route'    => 'validation/liste',
                        'visible'  => false,
                        'resource' => 'controller/Application\Controller\Validation:liste',
                        'pages' => [
                            'voir' => [
                                'label'  => "Détails",
                                'title'  => "Détails d'une validation",
                                'route'  => 'validation/voir',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:voir',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
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
                'Validation' => [],
            ],
        ],
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                        'Validation',
                        [AbstractAssertion::PRIVILEGE_READ],
                        'ValidationAssertion',
                    ],
                    [
                        [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                        'Validation',
                        [
                            AbstractAssertion::PRIVILEGE_CREATE,
                            AbstractAssertion::PRIVILEGE_DELETE,
                            AbstractAssertion::PRIVILEGE_UPDATE,
                        ],
                        'ValidationAssertion',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Validation' => 'Application\Controller\ValidationController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationTypeValidation'        => 'Application\\Service\\TypeValidation',
            'ApplicationValidation'            => 'Application\\Service\\Validation',
            'ValidationEnseignementRule'       => 'Application\\Rule\\Validation\\ValidationEnseignementRule',
            'ValidationReferentielRule'        => 'Application\\Rule\\Validation\\ValidationReferentielRule',
            'ValidationAssertion'              => 'Application\\Assertion\\ValidationAssertionProxy',
            'ValidationServiceAssertion'       => 'Application\\Assertion\\ValidationServiceAssertion',
            'ValidationReferentielAssertion'   => 'Application\\Assertion\\ValidationReferentielAssertion',
        ],
        'initializers' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];
