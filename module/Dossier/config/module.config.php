<?php

namespace Dossier;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Dossier\Assertion\IntervenantDossierAssertion;
use Dossier\Controller\AutresController;
use Dossier\Controller\EmployeurController;
use Dossier\Controller\IntervenantDossierController;
use Dossier\Form\AutresForm;
use Dossier\Form\EmployeurSaisieForm;
use Dossier\Form\Factory\AutresFormFactory;
use Dossier\Form\Factory\EmployeurSaisieFormFactory;
use Dossier\Form\Factory\IntervenantDossierFormFactory;
use Dossier\Form\IntervenantDossierForm;
use Dossier\Service\DossierAutreService;
use Dossier\Service\DossierAutreServiceFactory;
use Dossier\Service\DossierAutreTypeService;
use Dossier\Service\DossierAutreTypeServiceFactory;
use Dossier\Service\DossierService;
use Dossier\Service\DossierServiceFactory;
use Dossier\Service\EmployeurService;
use Dossier\Service\EmployeurServiceFactory;
use Dossier\View\Helper\ValidationViewHelperFactory;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'employeur'        => [
            'route'         => '/employeur',
            'controller'    => EmployeurController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'    => [
                    'route'       => '/saisie[/:employeur]',
                    'constraints' => [
                        'employeur' => '[0-9]*',
                    ],
                    'action'      => 'saisie',
                ],
                'supprimer' => [
                    'route'       => '/supprimer/:employeur',
                    'constraints' => [
                        'employeur' => '[0-9]*',
                    ],
                    'action'      => 'supprimer',
                ],
            ],
        ],
        'employeur-search' => [
            'route'         => '/employeur-search',
            'controller'    => EmployeurController::class,
            'action'        => 'recherche',
            'may_terminate' => true,
        ],
        'employeur-json'   => [
            'route'      => '/employeur/json',
            'controller' => EmployeurController::class,
            'action'     => 'recherche-json',
        ],
        'autres-infos'     => [
            'route'         => '/autres',
            'controller'    => AutresController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'route'       => '/saisie[/:dossierAutre]',
                    'constraints' => [
                        'dossierAutre' => '[0-9]*',
                    ],
                    'action'      => 'saisie',
                ],
            ],
        ],
        'intervenant'      => [
            'child_routes' => [
                'dossier' => [
                    'route'         => '/:intervenant/dossier',
                    'controller'    => IntervenantDossierController::class,
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        'change-statut-dossier' => [
                            'route'  => '/change-statut-dossier',
                            'action' => 'change-statut-dossier',
                        ],
                        'valider'               => [
                            'route'  => '/valider',
                            'action' => 'valider',
                        ],
                        'devalider'             => [
                            'route'  => '/devalider',
                            'action' => 'devalider',
                        ],

                        'supprimer'          => [
                            'route'  => '/supprimer',
                            'action' => 'supprimer',
                        ],
                        'differences'        => [
                            'route'  => '/differences',
                            'action' => 'differences',
                        ],
                        'purger-differences' => [
                            'route'  => '/purger-differences',
                            'action' => 'purger-differences',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'console-update-employeur' => [
            'route'      => 'update-employeur',
            'controller' => 'Application\Controller\Employeur',
            'action'     => 'update-employeur',
        ],
    ],


    'navigation'   => [
        'intervenant' => [
            'pages' => [
                'dossier' => [
                    'label'               => "Données personnelles",
                    'title'               => "Saisir les données personnelles d'un intervenant vacataire",
                    'route'               => 'intervenant/dossier',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_DONNEES_PERSO_SAISIE,
                    'withtarget'          => true,
                    'resource'            => PrivilegeController::getResourceId(IntervenantDossierController::class, 'index'),
                    'order'               => 5,
                ],
            ],
        ],

        'administration' => [
            'pages' => [
                'intervenants'  => [
                    'pages' => [
                        'gestion-champs-autres-dossier-intervenant' => [
                            'label'      => "Champs personnalisés du dossier",
                            'title'      => "Editer et modifier les 5 champs personnalisables pour les dossiers des intervenant",
                            'route'      => 'autres-infos',
                            'withtarget' => true,
                            'order'      => 10,
                            'resource'   => PrivilegeController::getResourceId(AutresController::class, 'index'),
                        ],
                    ],
                ],
                'nomenclatures' => [
                    'pages' => [
                        'Employeurs' => [
                            'color'    => '#9F491F',
                            'label'    => "Employeurs",
                            'title'    => "Gestion des employeurs",
                            'route'    => 'employeur',
                            'resource' => PrivilegeController::getResourceId(EmployeurController::class, 'index'),
                            'order'    => 20,
                        ],
                    ],
                ],
            ],
        ],
    ],
    'guards'       => [
        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['index', 'change-statut-dossier'],
            'privileges' => [Privileges::DOSSIER_VISUALISATION],
            'assertion'  => IntervenantDossierAssertion::class,
        ],

        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['differences'],
            'privileges' => [Privileges::DOSSIER_DIFFERENCES],
            'assertion'  => IntervenantDossierAssertion::class,
        ],
        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['purger-differences'],
            'privileges' => [Privileges::DOSSIER_PURGER_DIFFERENCES],
            'assertion'  => IntervenantDossierAssertion::class,
        ],
        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['valider'],
            'privileges' => [Privileges::DOSSIER_VALIDATION],
            'assertion'  => IntervenantDossierAssertion::class,

        ],
        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['devalider'],
            'privileges' => [Privileges::DOSSIER_DEVALIDATION],
            'assertion'  => IntervenantDossierAssertion::class,

        ],
        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['supprimer'],
            'privileges' => [Privileges::DOSSIER_SUPPRESSION],
            'assertion'  => IntervenantDossierAssertion::class,
        ],
        [
            'controller' => AutresController::class,
            'action'     => ['index'],
            'privileges' => Privileges::INTERVENANT_AUTRES_VISUALISATION,
        ],
        [
            'controller' => AutresController::class,
            'action'     => ['saisie'],
            'privileges' => Privileges::INTERVENANT_AUTRES_EDITION,
        ],
        [
            'controller' => EmployeurController::class,
            'action'     => ['index', 'recherche-json', 'saisie', 'supprimer'],
            'privileges' => [Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION],
        ],
    ],
    'rules'        => [
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
        [
            'privileges' => [
                Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION,
            ],
            'resources'  => ['Contrat'],
        ],

    ],
    'controllers'  => [
        IntervenantDossierController::class => Controller\Factory\IntervenantDossierControllerFactory::class,
        AutresController::class             => Controller\Factory\AutresControllerFactory::class,
        EmployeurController::class          => Controller\Factory\EmployeurControllerFactory::class,
    ],
    'services'     => [
        DossierService::class              => DossierServiceFactory::class,
        DossierAutreService::class         => DossierAutreServiceFactory::class,
        DossierAutreTypeService::class     => DossierAutreTypeServiceFactory::class,
        EmployeurService::class            => EmployeurServiceFactory::class,
        IntervenantDossierAssertion::class => AssertionFactory::class,

    ],
    'view_helpers' => [
        'validation' => ValidationViewHelperFactory::class,
    ],
    'forms'        => [
        IntervenantDossierForm::class => IntervenantDossierFormFactory::class,
        AutresForm::class             => AutresFormFactory::class,
        EmployeurSaisieForm::class    => EmployeurSaisieFormFactory::class,
    ],
];
