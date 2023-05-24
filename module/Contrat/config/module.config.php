<?php

namespace Contrat;

use Application\Provider\Privilege\Privileges;
use Contrat\Assertion\ContratAssertion;
use Contrat\Controller\ContratController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'contrat' => [
            'route'         => '/contrat',
            'controller'    => ContratController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'creer'               => [
                    'route'       => '/:intervenant/creer/:structure',
                    'constraints' => [
                        'structure' => '[0-9]*',
                    ],
                    'action'      => 'creer',
                    'controller'  => ContratController::class,
                ],
                'creer-mission'               => [
                    'route'       => '/:intervenant/creer-mission/:mission',
                    'constraints' => [
                        'mission' => '[0-9]*',
                    ],
                    'action'      => 'creer-mission',
                    'controller'  => ContratController::class,
                ],
                'supprimer'           => [
                    'route'       => '/:contrat/supprimer',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'supprimer',
                    'controller'  => ContratController::class,
                ],
                'valider'             => [

                    'route'       => '/:contrat/valider',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'valider',
                    'controller'  => ContratController::class,
                ],
                'devalider'           => [
                    'route'       => '/:contrat/devalider',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'devalider',
                    'controller'  => ContratController::class,
                ],
                'saisir-retour'       => [
                    'route'       => '/:contrat/saisir-retour',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'saisir-retour',
                    'controller'  => ContratController::class,
                ],
                'exporter'            => [
                    'route'       => '/:contrat/exporter',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'exporter',
                    'controller'  => ContratController::class,
                ],
                'envoyer-mail'        => [
                    'route'       => '/:contrat/mail',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'envoyer-mail',
                    'controller'  => ContratController::class,
                ],
                'deposer-fichier'     => [
                    'route'       => '/:contrat/deposer-fichier',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'deposer-fichier',
                    'controller'  => ContratController::class,
                ],
                'lister-fichier'      => [
                    'route'       => '/:contrat/lister-fichier',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'lister-fichier',
                    'controller'  => ContratController::class,
                ],
                'telecharger-fichier' => [
                    'route'       => '/:contrat/telecharger-fichier[/:fichier/:nomFichier]',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                    ],
                    'action'      => 'telecharger-fichier',
                    'controller'  => ContratController::class,
                ],
                'supprimer-fichier'   => [
                    'route'       => '/:contrat/supprimer-fichier[/:fichier]',
                    'constraints' => [
                        'contrat' => '[0-9]*',
                        'fichier' => '[0-9]*',
                    ],
                    'action'      => 'supprimer-fichier',
                    'controller'  => ContratController::class,
                ],
            ],
        ],
    ],
    'guards' => [
        [
            'controller' => ContratController::class,
            'action'     => ['index'],
            'privileges' => Privileges::CONTRAT_VISUALISATION,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['telecharger-fichier', 'lister-fichier'],
            'privileges' => Privileges::CONTRAT_VISUALISATION,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['exporter'],
            'privileges' => [Privileges::CONTRAT_CONTRAT_GENERATION, Privileges::CONTRAT_PROJET_GENERATION],
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['envoyer-mail'],
            'privileges' => [Privileges::CONTRAT_ENVOI_EMAIL],
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['creer'],
            'privileges' => Privileges::CONTRAT_CREATION,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['creer-mission'],
            'privileges' => Privileges::CONTRAT_CREATION,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['supprimer'],
            'privileges' => Privileges::CONTRAT_SUPPRESSION,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['valider'],
            'privileges' => Privileges::CONTRAT_VALIDATION,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['devalider'],
            'privileges' => Privileges::CONTRAT_DEVALIDATION,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['deposer-fichier', 'supprimer-fichier'],
            'privileges' => Privileges::CONTRAT_DEPOT_RETOUR_SIGNE,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
        [
            'controller' => ContratController::class,
            'action'     => ['saisir-retour'],
            'privileges' => Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE,
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],

    ],

    'navigation' => [

        'intervenant' => [
            'pages' => [
                'contrat' => [
                    'label' => "Contrat / avenant",
                    'title' => "Contrat et avenants de l'intervenant",
                    'route' => 'intervenant/contrat',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget' => true,
                    'resource' => PrivilegeController::getResourceId(ContratController::class, 'index'),
                    'order' => 12,
                ],
            ],
        ],

    ],

    'rules' => [
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
            'assertion'  => \Contrat\Assertion\ContratAssertion::class,
        ],
    ],

    'controllers'  => [
        ContratController::class => \Contrat\Controller\ContratControllerFactory::class,
    ],
    'services'     => [
        \Contrat\Assertion\ContratAssertion::class         => \UnicaenPrivilege\Assertion\AssertionFactory::class,
        \Contrat\Service\ContratService::class             => \Contrat\Service\ContratServiceFactory::class,
        \Contrat\Service\TypeContratService::class         => \Contrat\Service\TypeContratServiceFactory::class,
        \Contrat\Processus\ContratProcessus::class         => \Contrat\Processus\ContratProcessusFactory::class,
        \Contrat\Service\ContratServiceListeService::class => \Contrat\Service\ContratServiceListeServiceFactory::class,
    ],
    'view_helpers' => [
    ],
    'forms'        => [
        \Contrat\Form\ContratValidationForm::class => \Contrat\Form\ContratValidationFormFactory::class, /** @todo à supprimer ? */
        \Contrat\Form\ContratRetourForm::class     => \Contrat\Form\ContratRetourFormFactory::class,
    ],
];
