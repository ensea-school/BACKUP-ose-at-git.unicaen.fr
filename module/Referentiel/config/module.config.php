<?php

namespace Referentiel;

use Application\Entity\Db\WfEtape;
use Referentiel\Controller\FonctionReferentielController;
use Referentiel\Controller\ServiceReferentielController;
use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;


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
                            'type'          => 'Literal',
                            'options'       => [
                                'route'    => '/referentiel',
                                'defaults' => [
                                    'controller' => ServiceReferentielController::class,
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
                    'visible'             => Assertion\ServiceAssertion::class,
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
                    'visible'             => Assertion\ServiceAssertion::class,
                    'order'               => 15,
                ],
            ],
        ],


        'administration' => [
            'pages' => [
                'fonction-referentiel' => [
                    'label'        => 'Référentiel fonctions',
                    'icon'         => 'fas fa-graduation-cap',
                    'route'        => 'fonction-referentiel',
                    'resource'     => PrivilegeController::getResourceId(FonctionReferentielController::class, 'index'),
                    'order'        => 80,
                    'border-color' => '#BBCF55',
                ],
            ],
        ],
    ],

    'rules' => [
//        [
//            'privileges' => [
//                Privileges::REFERENTIEL_PREVU_VISUALISATION,
//                Privileges::REFERENTIEL_PREVU_EDITION,
//                Privileges::REFERENTIEL_REALISE_VISUALISATION,
//                Privileges::REFERENTIEL_REALISE_EDITION,
//            ],
//            'resources'  => ['ServiceReferentiel', 'Intervenant'],
//            'assertion'  => Assertion\ServiceAssertion::class,
//        ],
//        [
//            'privileges' => [
//                Privileges::REFERENTIEL_PREVU_VALIDATION,
//                Privileges::REFERENTIEL_REALISE_VALIDATION,
//                Privileges::REFERENTIEL_PREVU_AUTOVALIDATION,
//                Privileges::REFERENTIEL_REALISE_AUTOVALIDATION,
//            ],
//            'resources'  => ['ServiceReferentiel', 'VolumeHoraireReferentiel', 'Validation'],
//            'assertion'  => Assertion\ServiceAssertion::class,
//        ],
//        [
//            'privileges' => Privileges::REFERENTIEL_DEVALIDATION,
//            'resources'  => 'Validation',
//            'assertion'  => Assertion\ServiceAssertion::class,
//        ],
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
            'privileges' => [Privileges::REFERENTIEL_ADMIN_EDITION],
        ],


        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['prevu'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
            ],
            //      'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['realise'],
            'privileges' => [
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
            ],
            //      'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'initialisation', 'constatation'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
            ],
            //      'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => ServiceReferentielController::class,
            'action'     => ['validation'],
            'privileges' => [
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
            ],
            //         'assertion'  => Assertion\ServiceAssertion::class,
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
        Assertion\ServiceAssertion::class               => AssertionFactory::class,
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