<?php

namespace Application;

use Application\Assertion\ContratAssertion;
use Application\Controller\Factory\ContratControllerFactory;
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
                        'controller' => 'Application\Controller\Contrat',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'creer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:intervenant/creer/:structure',
                            'constraints' => [
                                'structure' => '[0-9]*',
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
                    'envoyer-mail'        => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:contrat/mail',
                            'constraints' => [
                                'contrat' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'envoyer-mail',
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
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['index'],
                    'privileges' => Privileges::CONTRAT_VISUALISATION,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['telecharger-fichier', 'lister-fichier'],
                    'privileges' => Privileges::CONTRAT_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['exporter'],
                    'privileges' => [Privileges::CONTRAT_CONTRAT_GENERATION, Privileges::CONTRAT_PROJET_GENERATION],
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['envoyer-mail'],
                    'privileges' => [Privileges::CONTRAT_ENVOI_EMAIL],
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['creer'],
                    'privileges' => Privileges::CONTRAT_CREATION,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['supprimer'],
                    'privileges' => Privileges::CONTRAT_SUPPRESSION,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['valider'],
                    'privileges' => Privileges::CONTRAT_VALIDATION,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['devalider'],
                    'privileges' => Privileges::CONTRAT_DEVALIDATION,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['deposer-fichier', 'supprimer-fichier'],
                    'privileges' => Privileges::CONTRAT_DEPOT_RETOUR_SIGNE,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => ['saisir-retour'],
                    'privileges' => Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE,
                    'assertion'  => Assertion\ContratAssertion::class,
                ],
            ],
        ],
        
        'rule_providers' => [
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
                            Privileges::CONTRAT_PROJET_GENERATION,
                            Privileges::CONTRAT_CONTRAT_GENERATION,
                            Privileges::CONTRAT_ENVOI_EMAIL,
                            ContratAssertion::PRIV_LISTER_FICHIERS,
                            ContratAssertion::PRIV_AJOUTER_FICHIER,
                            ContratAssertion::PRIV_SUPPRIMER_FICHIER,
                            ContratAssertion::PRIV_EXPORT,
                        ],
                        'resources'  => 'Contrat',
                        'assertion'  => Assertion\ContratAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\Contrat' => Controller\Factory\ContratControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories'  => [
            Assertion\ContratAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
        'invokables' => [
            Service\ContratService::class     => Service\ContratService::class,
            Service\TypeContratService::class => Service\TypeContratService::class,
            Processus\ContratProcessus::class => Processus\ContratProcessus::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Intervenant\ContratValidation::class => Form\Intervenant\ContratValidation::class, /** @todo à supprimer ? */
            Form\Intervenant\ContratRetour::class     => Form\Intervenant\ContratRetour::class,
        ],
    ],
];
