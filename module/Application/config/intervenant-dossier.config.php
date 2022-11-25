<?php

namespace Application;

use Application\Assertion\IntervenantDossierAssertion;
use Application\Form\Intervenant\Factory\IntervenantDossierFactory;
use Application\Form\Intervenant\Factory\IntervenantDossierFormFactory;
use Application\Form\Intervenant\IntervenantDossier;
use Application\Form\Intervenant\IntervenantDossierForm;
use Application\Provider\Privilege\Privileges;
use Application\Service\AdresseNumeroComplService;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [

            'intervenant' => [
                'child_routes' => [
                    'dossier' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/:intervenant/dossier',
                            'defaults' => [
                                'controller' => 'Application\Controller\IntervenantDossier',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'change-statut-dossier' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/change-statut-dossier',
                                    'defaults' => [
                                        'action' => 'change-statut-dossier',
                                    ],
                                ],
                            ],
                            'valider'               => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/valider',
                                    'defaults' => [
                                        'action' => 'valider',
                                    ],
                                ],
                            ],
                            'devalider'             => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/devalider',
                                    'defaults' => [
                                        'action' => 'devalider',
                                    ],
                                ],
                            ],
                            'supprimer'             => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/supprimer',
                                    'defaults' => [
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'differences'           => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/differences',
                                    'defaults' => [
                                        'action' => 'differences',
                                    ],
                                ],
                            ],
                            'purger-differences'    => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/purger-differences',
                                    'defaults' => [
                                        'action' => 'purger-differences',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'calcul-completude-dossier' => [
                    'options' => [
                        'route'    => 'calcul-completude-dossier [--annee=] [--intervenant=]',
                        'defaults' => [
                            'controller' => 'Application\Controller\IntervenantDossier',
                            'action'     => 'calcul-completude-dossier',
                        ],
                    ],
                ],
            ],
        ],
    ]
    ,
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'intervenant' => [
                        'pages' => [
                            'dossier' => [
                                'label'        => "Données personnelles",
                                'title'        => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'        => 'intervenant/dossier',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Dossier', 'index'),
                                'order'        => 5,
                            ],
                        ],
                    ],

                    'administration' => [
                        'pages' => [
                            'intervenants' => [
                                'pages' => [
                                    'gestion-champs-autres-dossier-intervenant' => [
                                        'label'      => "Champs personnalisés du dossier",
                                        'title'      => "Editer et modifier les 5 champs personnalisables pour les dossiers des intervenant",
                                        'route'      => 'autres-infos',
                                        'withtarget' => true,
                                        'order'      => 10,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\Autres', 'index'),
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
        'guards'         => [
            PrivilegeController::class => [
                /* Dossier */
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['index', 'change-statut-dossier'],
                    'privileges' => [Privileges::DOSSIER_VISUALISATION],
                    'assertion'  => IntervenantDossierAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['differences'],
                    'privileges' => [Privileges::DOSSIER_DIFFERENCES],
                    'assertion'  => IntervenantDossierAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['purger-differences'],
                    'privileges' => [Privileges::DOSSIER_PURGER_DIFFERENCES],
                    'assertion'  => IntervenantDossierAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['valider'],
                    'privileges' => [Privileges::DOSSIER_VALIDATION],
                    'assertion'  => IntervenantDossierAssertion::class,

                ],
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['devalider'],
                    'privileges' => [Privileges::DOSSIER_DEVALIDATION],
                    'assertion'  => IntervenantDossierAssertion::class,

                ],
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['supprimer'],
                    'privileges' => [Privileges::DOSSIER_SUPPRESSION],
                    'assertion'  => IntervenantDossierAssertion::class,
                ],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            IntervenantDossierAssertion::PRIV_VIEW_IDENTITE,
                            IntervenantDossierAssertion::PRIV_EDIT_IDENTITE,
                            IntervenantDossierAssertion::PRIV_EDIT_ADRESSE,
                            IntervenantDossierAssertion::PRIV_VIEW_ADRESSE,
                            IntervenantDossierAssertion::PRIV_EDIT_CONTACT,
                            IntervenantDossierAssertion::PRIV_VIEW_CONTACT,
                            IntervenantDossierAssertion::PRIV_EDIT_INSEE,
                            IntervenantDossierAssertion::PRIV_VIEW_INSEE,
                            IntervenantDossierAssertion::PRIV_VIEW_IBAN,
                            IntervenantDossierAssertion::PRIV_EDIT_IBAN,
                            IntervenantDossierAssertion::PRIV_VIEW_EMPLOYEUR,
                            IntervenantDossierAssertion::PRIV_EDIT_EMPLOYEUR,
                            IntervenantDossierAssertion::PRIV_VIEW_AUTRE1,
                            IntervenantDossierAssertion::PRIV_EDIT_AUTRE1,
                            IntervenantDossierAssertion::PRIV_VIEW_AUTRE2,
                            IntervenantDossierAssertion::PRIV_EDIT_AUTRE2,
                            IntervenantDossierAssertion::PRIV_VIEW_AUTRE3,
                            IntervenantDossierAssertion::PRIV_EDIT_AUTRE3,
                            IntervenantDossierAssertion::PRIV_VIEW_AUTRE4,
                            IntervenantDossierAssertion::PRIV_EDIT_AUTRE4,
                            IntervenantDossierAssertion::PRIV_VIEW_AUTRE5,
                            IntervenantDossierAssertion::PRIV_EDIT_AUTRE5,
                            IntervenantDossierAssertion::PRIV_CAN_VALIDE,
                            IntervenantDossierAssertion::PRIV_CAN_DEVALIDE,
                            IntervenantDossierAssertion::PRIV_CAN_EDIT,
                            IntervenantDossierAssertion::PRIV_CAN_SUPPRIME,


                        ],
                        'resources'  => ['Intervenant'],
                        'assertion'  => IntervenantDossierAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\IntervenantDossier' => Controller\Factory\IntervenantDossierControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\DossierService::class    => Service\DossierService::class,
            AdresseNumeroComplService::class => AdresseNumeroComplService::class,
        ],
        'factories'  => [
            Assertion\IntervenantDossierAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'validation' => View\Helper\ValidationViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            IntervenantDossierForm::class => IntervenantDossierFormFactory::class,
        ],
    ],
];
