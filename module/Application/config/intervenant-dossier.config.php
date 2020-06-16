<?php

namespace Application;

use Application\Assertion\IntervenantDossierAssertion;
use Application\Form\Intervenant\Factory\IntervenantDossierFactory;
use Application\Form\Intervenant\Factory\IntervenantDossierFormFactory;
use Application\Form\Intervenant\IntervenantDossier;
use Application\Form\Intervenant\IntervenantDossierForm;
use Application\Provider\Privilege\Privileges;
use Application\Service\AdresseNumeroComplService;
use Application\Service\DossierAutreService;
use Application\Service\IntervenantDossierService;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [

            'intervenant'   => [
                'child_routes' => [
                    'dossier' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/:intervenant/intervenant-dossier',
                            'defaults' => [
                                'controller' => 'Application\Controller\IntervenantDossier',
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'valider' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/valider',
                                    'defaults' => [
                                        'action' => 'valider',
                                    ],
                                ],
                            ],
                            /*'devalider'          => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/devalider',
                                    'defaults' => [
                                        'action' => 'devalider',
                                    ],
                                ],
                            ],
                            'supprimer'          => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/supprimer',
                                    'defaults' => [
                                        'action' => 'supprimer',
                                    ],
                                ],
                            ],
                            'differences'        => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/differences',
                                    'defaults' => [
                                        'action' => 'differences',
                                    ],
                                ],
                            ],
                            'purger-differences' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/purger-differences',
                                    'defaults' => [
                                        'action' => 'purger-differences',
                                    ],
                                ],
                            ],*/
                        ],
                    ],
                ],
            ],
            'dossier-autre' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/dossier-autre',
                    'defaults' => [
                        'controller' => 'Application\Controller\IntervenantDossier',
                        'action'     => 'dossier-autre-info',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
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
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                /* Dossier */
                /*[//Créer un droit archivage
                 'controller' => 'Application\Controller\Dossier',
                 'action'     => ['index'],
                 'privileges' => [Privileges::DOSSIER_VISUALISATION],
                 'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['differences'],
                    'privileges' => [Privileges::DOSSIER_DIFFERENCES],
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['purger-differences'],
                    'privileges' => [Privileges::DOSSIER_PURGER_DIFFERENCES],
                    'assertion'  => Assertion\DossierPiecesAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['valider'],
                    'privileges' => [Privileges::DOSSIER_VALIDATION],
                ],
                [
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => ['supprimer'],
                    'privileges' => [Privileges::DOSSIER_SUPPRESSION],
                ],*/
                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['valider', 'devalider'],
                    'privileges' => [Privileges::DOSSIER_VALIDATION],
                    'assertion'  => IntervenantDossierAssertion::class,
                ],

                [//Créer un droit archivage
                 'controller' => 'Application\Controller\IntervenantDossier',
                 'action'     => ['index'],
                 'privileges' => [Privileges::DOSSIER_VISUALISATION, Privileges::DOSSIER_IDENTITE_SUITE_EDITION, Privileges::DOSSIER_IDENTITE_SUITE_VISUALISATION],
                 'assertion'  => IntervenantDossierAssertion::class,
                ],

                [
                    'controller' => 'Application\Controller\IntervenantDossier',
                    'action'     => ['dossier-autre-info'],
                    'privileges' => [Privileges::DOSSIER_VALIDATION],
                    'assertion'  => IntervenantDossierAssertion::class,
                ],

            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'PieceJointe' => [],
            ],
        ],
        'rule_providers'     => [
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
                            IntervenantDossierAssertion::PRIV_EDIT_EMPLOYEUR,
                            IntervenantDossierAssertion::PRIV_VIEW_EMPLOYEUR,
                            IntervenantDossierAssertion::PRIV_EDIT_INSEE,
                            IntervenantDossierAssertion::PRIV_VIEW_INSEE,
                            IntervenantDossierAssertion::PRIV_EDIT_IBAN,
                            IntervenantDossierAssertion::PRIV_VIEW_IBAN,
                            IntervenantDossierAssertion::PRIV_CAN_VALIDE,
                            IntervenantDossierAssertion::PRIV_CAN_DEVALIDE,


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
            IntervenantDossierService::class => IntervenantDossierService::class,
            AdresseNumeroComplService::class => AdresseNumeroComplService::class,
            DossierAutreService::class       => DossierAutreService::class,
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
        /*'invokables' => [
            Form\Intervenant\IntervenantDossier::class => Form\Intervenant\IntervenantDossier::class,
        ],*/
    ],
];
