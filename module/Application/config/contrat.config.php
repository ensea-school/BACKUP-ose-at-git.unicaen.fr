<?php

namespace Application;

use Application\Assertion\ContratAssertion;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'contrat' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/contrat',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contrat',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'creer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/creer/:structure',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                                'structure'   => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'creer',
                            ],
                        ],
                    ],
                    'supprimer'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/supprimer',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'valider'             => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/valider',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'valider',
                            ],
                        ],
                    ],
                    'devalider'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/devalider',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'devalider',
                            ],
                        ],
                    ],
                    'saisir-retour'       => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/saisir-retour',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir-retour',
                            ],
                        ],
                    ],
                    'exporter'            => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/exporter',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'exporter',
                            ],
                        ],
                    ],
                    'deposer-fichier'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/deposer-fichier',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'deposer-fichier',
                            ],
                        ],
                    ],
                    'lister-fichier'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/lister-fichier',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'lister-fichier',
                            ],
                        ],
                    ],
                    'telecharger-fichier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/telecharger-fichier[/:fichier/:nomFichier]',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'telecharger-fichier',
                            ],
                        ],
                    ],
                    'supprimer-fichier'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/supprimer-fichier[/:fichier]',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                                'fichier' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer-fichier',
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
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['index', 'telecharger-fichier', 'lister-fichier'],
                    'privileges' => Privileges::CONTRAT_VISUALISATION,
                    'assertion'  => 'assertionContrat',
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['creer'],
                    'privileges' => Privileges::CONTRAT_CREATION,
                    'assertion'  => 'assertionContrat',
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['supprimer'],
                    'privileges' => Privileges::CONTRAT_SUPPRESSION,
                    'assertion'  => 'assertionContrat',
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['valider'],
                    'privileges' => Privileges::CONTRAT_VALIDATION,
                    'assertion'  => 'assertionContrat',
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['devalider'],
                    'privileges' => Privileges::CONTRAT_DEVALIDATION,
                    'assertion'  => 'assertionContrat',
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['exporter', 'deposer-fichier', 'supprimer-fichier'],
                    'privileges' => Privileges::CONTRAT_DEPOT_RETOUR_SIGNE,
                    'assertion'  => 'assertionContrat',
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['saisir-retour'],
                    'privileges' => Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE,
                    'assertion'  => 'assertionContrat',
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Contrat' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::CONTRAT_CREATION,
                            Privileges::CONTRAT_DEPOT_RETOUR_SIGNE,
                            Privileges::CONTRAT_DEVALIDATION,
                            Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE,
                            Privileges::CONTRAT_SUPPRESSION,
                            Privileges::CONTRAT_VALIDATION,
                            Privileges::CONTRAT_VISUALISATION,
                            ContratAssertion::PRIV_LISTER_FICHIERS,
                            ContratAssertion::PRIV_AJOUTER_FICHIER,
                            ContratAssertion::PRIV_SUPPRIMER_FICHIER,
                        ],
                        'resources'  => 'Contrat',
                        'assertion'  => 'assertionContrat',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Contrat' => Controller\ContratController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationContrat'          => Service\Contrat::class,
            'ApplicationTypeContrat'      => Service\TypeContrat::class,
            'assertionContrat'            => Assertion\ContratAssertion::class,
            'processusContrat'            => Processus\ContratProcessus::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'contratValivation' => Form\Intervenant\ContratValidation::class,
            'contratRetour'     => Form\Intervenant\ContratRetour::class,
        ],
    ],
];
