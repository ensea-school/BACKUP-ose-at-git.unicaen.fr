<?php

namespace Referentiel;

use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Referentiel\Controller\FonctionReferentielController;
use Referentiel\Controller\ServiceReferentielController;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Workflow\Entity\Db\WfEtape;


return [
    'routes' => [
        'fonction-referentiel' => [
            'route'         => '/fonction-referentiel',
            'controller'    => FonctionReferentielController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'route'         => '/saisie[/:fonctionReferentiel]',
                    'action'        => 'saisie',
                    'may_terminate' => true,
                    'constraints'   => [
                        'fonctionReferentiel' => '[0-9]*',
                    ],
                ],
                'delete' => [
                    'route'         => '/delete/:fonctionReferentiel',
                    'action'        => 'delete',
                    'may_terminate' => true,
                    'constraints'   => [
                        'fonctionReferentiel' => '[0-9]*',
                    ],

                ],
            ],
        ],

        'intervenant' => [
            'child_routes' => [
                'referentiel-prevu'   => [
                    'route'      => '/:intervenant/referentiel-prevu',
                    'controller' => ServiceReferentielController::class,
                    'action'     => 'prevu',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'PREVU',
                    ],
                ],
                'referentiel-realise' => [
                    'route'      => '/:intervenant/referentiel',
                    'controller' => ServiceReferentielController::class,
                    'action'     => 'realise',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'REALISE',
                    ],
                ],

                'validation' => [
                    'child_routes' => [
                        'referentiel' => [
                            'route'         => '/referentiel',
                            'controller'    => ServiceReferentielController::class,
                            'may_terminate' => false,
                            'child_routes'  => [
                                'prevu'     => [
                                    'route'  => '/prevu',
                                    'action' => 'validation-prevu',
                                ],
                                'realise'   => [
                                    'route'  => '/realise',
                                    'action' => 'validation-realise',
                                ],
                                'valider'   => [
                                    'route'       => '/valider/:typeVolumeHoraire/:structure',
                                    'action'      => 'valider',
                                    'constraints' => [
                                        'typeVolumeHoraire' => '[0-9]*',
                                        'structure'         => '[0-9]*',
                                    ],
                                ],
                                'devalider' => [
                                    'route'       => '/devalider/:validation',
                                    'action'      => 'devalider',
                                    'constraints' => [
                                        'validation' => '[0-9]*',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],

        'referentiel' => [
            'route'         => '/referentiel',
            'controller'    => ServiceReferentielController::class,
            'may_terminate' => false,
            'child_routes'  => [
                'saisie'                   => [
                    'route'       => '/saisie[/:id]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'id' => '[0-9]*',
                    ],
                ],
                'volumes-horaires-refresh' => [
                    'route'       => '/volumes-horaires-refresh[/:id]',
                    'action'      => 'volumes-horaires-refresh',
                    'constraints' => [
                        'id' => '[0-9]*',
                    ],
                ],
                'rafraichir-ligne'         => [
                    'route'       => '/rafraichir-ligne/:serviceReferentiel',
                    'action'      => 'rafraichir-ligne',
                    'constraints' => [
                        'serviceReferentiel' => '[0-9]*',
                    ],
                ],
                'constatation'             => [
                    'route'  => '/constatation',
                    'action' => 'constatation',
                ],
                'suppression'              => [
                    'route'       => '/suppression/:id',
                    'action'      => 'suppression',
                    'constraints' => [
                        'id' => '[0-9]*',
                    ],
                ],
                'initialisation'           => [
                    'route'       => '/initialisation/:intervenant',
                    'action'      => 'initialisation',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                'validation-referentiel-prevu'   => [
                    'label'               => "Validation du référentiel prévisionnel",
                    'title'               => "Validation du référentiel prévisionnel de l'intervenant",
                    'route'               => 'intervenant/validation/referentiel/prevu',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_REFERENTIEL_VALIDATION,
                    'withtarget'          => true,
                    'visible'             => Assertion\ReferentielAssertion::class,
                    'order'               => 9,
                ],
                'validation-referentiel-realise' => [
                    'label'               => "Validation du référentiel réalisé",
                    'title'               => "Validation du référentiel réalisé de l'intervenant",
                    'route'               => 'intervenant/validation/referentiel/realise',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_REFERENTIEL_VALIDATION_REALISE,
                    'withtarget'          => true,
                    'visible'             => Assertion\ReferentielAssertion::class,
                    'order'               => 15,
                ],
            ],
        ],


        'administration' => [
            'pages' => [
                'rh' => [
                    'pages' => [
                        'fonction-referentiel' => [
                            'label'    => 'Référentiel fonctions',
                            'route'    => 'fonction-referentiel',
                            'resource' => PrivilegeController::getResourceId(FonctionReferentielController::class, 'index'),
                            'order'    => 70,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
                Privileges::REFERENTIEL_REALISE_EDITION,
                Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
            ],
            'resources'  => ['ServiceReferentiel', 'Intervenant'],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VALIDATION,
                Privileges::REFERENTIEL_REALISE_VALIDATION,
                Privileges::REFERENTIEL_PREVU_AUTOVALIDATION,
                Privileges::REFERENTIEL_REALISE_AUTOVALIDATION,
            ],
            'resources'  => ['ServiceReferentiel', 'VolumeHoraireReferentiel', 'Validation'],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'privileges' => Privileges::REFERENTIEL_DEVALIDATION,
            'resources'  => 'Validation',
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'privileges' => Privileges::REFERENTIEL_ADMIN_EDITION,
            'resources'  => 'FonctionReferentiel',
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => FonctionReferentielController::class,
            'action'     => ['index'],
            'privileges' => [Privileges::REFERENTIEL_ADMIN_VISUALISATION],
        ],
        [
            'controller' => FonctionReferentielController::class,
            'action'     => ['saisie', 'delete'],
            'privileges' => [Privileges::REFERENTIEL_ADMIN_EDITION,],
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['prevu'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
            ],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['realise'],
            'privileges' => [
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
            ],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'initialisation', 'constatation'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
                Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
            ],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['validation-prevu'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
            ],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['validation-realise'],
            'privileges' => [
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
            ],
            'assertion'  => Assertion\ReferentielAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['valider'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VALIDATION,
                Privileges::REFERENTIEL_REALISE_VALIDATION,
            ],
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['devalider'],
            'privileges' => [
                Privileges::REFERENTIEL_DEVALIDATION,
            ],
        ],
    ],

    'controllers' => [
        ServiceReferentielController::class  => InvokableFactory::class,
        FonctionReferentielController::class => InvokableFactory::class,
    ],

    'services' => [
        Service\FonctionReferentielService::class       => InvokableFactory::class,
        Service\ServiceReferentielService::class        => InvokableFactory::class,
        Service\VolumeHoraireReferentielService::class  => InvokableFactory::class,
        Processus\ServiceReferentielProcessus::class    => InvokableFactory::class,
        Processus\ValidationReferentielProcessus::class => InvokableFactory::class,
        Assertion\ReferentielAssertion::class           => AssertionFactory::class,
    ],

    'forms' => [
        Form\FonctionReferentielSaisieForm::class => InvokableFactory::class,
        Form\Saisie::class                        => InvokableFactory::class,
        Form\SaisieFieldset::class                => InvokableFactory::class,
    ],

    'view_helpers' => [
        'formServiceReferentielSaisie' => View\Helper\FormSaisieFactory::class,
        'fonctionReferentiel'          => View\Helper\FonctionReferentielFactory::class,
        'referentiels'                 => View\Helper\ReferentielsFactory::class,
        'serviceReferentielLigne'      => View\Helper\LigneFactory::class,

    ],
];