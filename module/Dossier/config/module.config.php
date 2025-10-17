<?php

namespace Dossier;

use Application\Provider\Privileges;
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
use Dossier\Tbl\Process\DossierProcess;
use Dossier\Tbl\Process\DossierProcessFactory;
use Dossier\View\Helper\ValidationViewHelperFactory;
use Unicaen\Framework\Authorize\Authorize;

return [
    'routes' => [
        'employeur'        => [
            'route'         => '/employeur',
            'controller'    => EmployeurController::class,
            'action'        => 'employeur',
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
        'employeur-data'   => [
            'route'         => '/employeur/get-data',
            'controller'    => EmployeurController::class,
            'action'        => 'data-employeur',
            'may_terminate' => true,
        ],
        'employeur-search' => [
            'route'         => '/employeur-search',
            'controller'    => EmployeurController::class,
            'action'        => 'recherche',
            'may_terminate' => true,
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
                        'change-statut-dossier'    => [
                            'route'  => '/change-statut-dossier',
                            'action' => 'change-statut-dossier',
                        ],
                        'valider'                  => [
                            'route'  => '/valider',
                            'action' => 'valider',
                        ],
                        'valider-complementaire'   => [
                            'route'  => '/valider-complementaire',
                            'action' => 'valider-complementaire',
                        ],
                        'devalider'                => [
                            'route'  => '/devalider',
                            'action' => 'devalider',
                        ],
                        'devalider-complementaire' => [
                            'route'  => '/devalider-complementaire',
                            'action' => 'devalider-complementaire',
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
                    'label' => "Données personnelles",
                    'title' => "Saisir les données personnelles d'un intervenant vacataire",
                    'route' => 'intervenant/dossier',
                    'order' => 5,
                ],
            ],
        ],

        'administration' => [
            'pages' => [
                'intervenants'  => [
                    'pages' => [
                        'gestion-champs-autres-dossier-intervenant' => [
                            'label'    => "Champs personnalisés des données personnelles",
                            'title'    => "Éditer et modifier les 5 champs personnalisables pour les données personnelles des intervenants",
                            'route'    => 'autres-infos',
                            'order'    => 10,
                            'resource' => Authorize::controllerResource(AutresController::class, 'index'),
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
                            'resource' => Authorize::controllerResource(EmployeurController::class, 'index'),
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
            'action'     => ['valider-complementaire'],
            'privileges' => [Privileges::DOSSIER_VALIDATION_COMP],
            'assertion'  => IntervenantDossierAssertion::class,

        ],
        [
            'controller' => IntervenantDossierController::class,
            'action'     => ['devalider-complementaire'],
            'privileges' => [Privileges::DOSSIER_DEVALIDATION_COMP],
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
            'action'     => ['index',
                             'saisie',
                             'supprimer',
                             'employeur'],
            'privileges' => [Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION],
            'assertion'  => IntervenantDossierAssertion::class,

        ],
        [
            'controller' => EmployeurController::class,
            'action'     => ['recherche',
                             'data-employeur'],
            'privileges' => ['user'],

        ],
    ],
    'rules'        => [
        [
            'privileges' => [
                Privileges::DOSSIER_EDITION,
                Privileges::DOSSIER_ADRESSE_EDITION,
                Privileges::DOSSIER_ADRESSE_VISUALISATION,
                Privileges::DOSSIER_BANQUE_EDITION,
                Privileges::DOSSIER_BANQUE_VISUALISATION,
                Privileges::DOSSIER_CHAMP_AUTRE_1_EDITION,
                Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION,
                Privileges::DOSSIER_CHAMP_AUTRE_2_EDITION,
                Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION,
                Privileges::DOSSIER_CHAMP_AUTRE_3_EDITION,
                Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION,
                Privileges::DOSSIER_CHAMP_AUTRE_4_EDITION,
                Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION,
                Privileges::DOSSIER_CHAMP_AUTRE_5_EDITION,
                Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION,
                Privileges::DOSSIER_CONTACT_EDITION,
                Privileges::DOSSIER_CONTACT_VISUALISATION,
                Privileges::DOSSIER_DEVALIDATION,
                Privileges::DOSSIER_DEVALIDATION_COMP,
                Privileges::DOSSIER_DIFFERENCES,
                Privileges::DOSSIER_EDITION,
                Privileges::DOSSIER_EDITION_COMP,
                Privileges::DOSSIER_EMPLOYEUR_EDITION,
                Privileges::DOSSIER_EMPLOYEUR_VISUALISATION,
                Privileges::DOSSIER_IDENTITE_EDITION,
                Privileges::DOSSIER_IDENTITE_VISUALISATION,
                Privileges::DOSSIER_INSEE_EDITION,
                Privileges::DOSSIER_INSEE_VISUALISATION,
                Privileges::DOSSIER_PURGER_DIFFERENCES,
                Privileges::DOSSIER_SUPPRESSION,
                Privileges::DOSSIER_VALIDATION,
                Privileges::DOSSIER_VALIDATION_COMP,
                Privileges::DOSSIER_VISUALISATION,
                Privileges::DOSSIER_VISUALISATION_COMP,
            ],
            'resources'  => ['IntervenantDossier'],
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
        DossierService::class          => DossierServiceFactory::class,
        DossierAutreService::class     => DossierAutreServiceFactory::class,
        DossierAutreTypeService::class => DossierAutreTypeServiceFactory::class,
        EmployeurService::class        => EmployeurServiceFactory::class,
        DossierProcess::class          => DossierProcessFactory::class,

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
