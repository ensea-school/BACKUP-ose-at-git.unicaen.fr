<?php

namespace OffreFormation;

use Application\Provider\Privilege\Privileges;
use OffreFormation\Controller\ElementPedagogiqueController;
use OffreFormation\Controller\EtapeCentreCoutController;
use OffreFormation\Controller\EtapeController;
use OffreFormation\Controller\ModulateurController;
use OffreFormation\Controller\OffreFormationController;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'routes' => [
        'aof' => [
            'route'         => '/administration-offre',
            'controller'    => OffreFormationController::class,
            'action'        => 'administrationOffre',
            'may_terminate' => true,
            'child_routes'  => [
                'reconduction'             => [
                    'route'      => '/reconduction',
                    'controller' => OffreFormationController::class,
                    'action'     => 'reconduction',
                ],
                'reconduction-centre-cout' => [
                    'route'      => '/reconduction-centre-cout',
                    'controller' => OffreFormationController::class,
                    'action'     => 'reconductionCentreCout',
                ],
                'reconduction-modulateur'  => [
                    'route'      => '/reconduction-modulateur',
                    'controller' => OffreFormationController::class,
                    'action'     => 'reconductionModulateur',
                ],
            ],
        ],
        'of'  => [
            'route'         => '/offre-de-formation',
            'order'         => 2,
            'controller'    => OffreFormationController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'default' => [
                    'action'      => 'index',
                    'route'       => '/:action[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]*',
                    ],
                ],
                'element' => [
                    'route'         => '/element',
                    'controller'    => OffreFormationController::class,
                    'may_terminate' => false,
                    'child_routes'  => [
                        'voir'      => [
                            'route'       => '/voir/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'voir',
                        ],
                        'ajouter'   => [
                            'route'      => '/ajouter',
                            'controller' => OffreFormationController::class,
                            'action'     => 'saisir',
                        ],
                        'modifier'  => [
                            'route'       => '/modifier/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'saisir',
                        ],
                        'supprimer' => [
                            'route'       => '/supprimer/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'supprimer',
                        ],
                        'search'    => [
                            'route'      => '/search',
                            'controller' => OffreFormationController::class,
                            'action'     => 'search',
                        ],

                        'get-periode'               => [
                            'route'       => '/get-periode/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'getPeriode',
                        ],
                        'volume-horaire'            => [
                            'route'       => '/volume-horaire/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'volume-horaire',
                        ],
                        'synchronisation'           => [
                            'route'       => '/synchronisation/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'synchronisation',
                        ],
                        'synchronisation-par-code'  => [
                            'route'      => '/synchronisation-par-code/:elementPedagogique',
                            'controller' => OffreFormationController::class,
                            'action'     => 'synchronisation-par-code',
                        ],
                        'modulateurs-centres-couts' => [
                            'route'       => '/modulateurs-centres-couts/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'modulateurs-centres-couts',
                        ],
                        'modifier-modulateurs'      => [
                            'route'       => '/modulateurs/modifier/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => OffreFormationController::class,
                            'action'      => 'modifier-modulateur',
                        ],
                    ],
                ],
                'etape'   => [
                    'route'         => '/etape',
                    'controller'    => EtapeController::class,
                    'may_terminate' => false,
                    'child_routes'  => [
                        'voir'      => [
                            'route'       => '/voir/:etape',
                            'constraints' => ['etape' => '[0-9]*'],
                            'controller'  => EtapeController::class,
                            'action'      => 'voir',
                        ],
                        'ajouter'   => [
                            'route'       => '/ajouter/:structure',
                            'constraints' => ['structure' => '[0-9]*'],
                            'controller'  => EtapeController::class,
                            'action'      => 'saisir',
                        ],
                        'restaurer' => [
                            'route'       => '/restaurer/:etape',
                            'constraints' => ['etape' => '[0-9]*'],
                            'controller'  => EtapeController::class,
                            'action'      => 'restaurer',
                        ],

                        'modifier'      => [
                            'route'       => '/modifier/:etape',
                            'constraints' => ['etape' => '[0-9]*'],
                            'controller'  => EtapeController::class,
                            'action'      => 'saisir',
                        ],
                        'supprimer'     => [
                            'route'       => '/supprimer/:etape',
                            'constraints' => ['etape' => '[0-9]*'],
                            'controller'  => EtapeController::class,
                            'action'      => 'supprimer',
                        ],
                        'modulateurs'   => [
                            'route'       => '/modulateurs/:etape',
                            'constraints' => ['etape' => '[0-9]*'],
                            'controller'  => ModulateurController::class,
                            'action'      => 'saisir',
                        ],
                        'centres-couts' => [
                            'route'       => '/centres-couts/:etape',
                            'constraints' => [
                                'etape' => '[0-9]*',
                            ],
                            'controller'  => EtapeCentreCoutController::class,
                            'action'      => 'saisir',
                        ],
                        'taux-mixite'   => [
                            'route'       => '/taux-mixite/:etape',
                            'constraints' => [
                                'etape' => '[0-9]*',
                            ],
                            'controller'  => EtapeController::class,
                            'action'      => 'taux-mixite',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'of'             => [
            'label'    => 'Offre de formation',
            'title'    => "Gestion de l'offre de formation",
            'route'    => 'of',
            'order'    => 2,
            'resource' => PrivilegeController::getResourceId(OffreFormationController::class, 'index'),
        ],
        'administration' => [
            'pages' => [
                'odf' => [
                    'pages' => [
                        'reconduction-offre'       => [
                            'label'    => 'Reconduction de l\'offre de formation complémentaire',
                            'title'    => 'Reconduction de l\'offre de formation complémentaire',
                            'route'    => 'aof/reconduction',
                            'order'    => 20,
                            'resource' => PrivilegeController::getResourceId(OffreFormationController::class, 'reconduction'),
                        ],
                        'reconduction-centre-cout' => [
                            'label'    => 'Reconduction des centres de coûts',
                            'title'    => 'Reconduction des centres de coûts de l\'offre de formation',
                            'route'    => 'aof/reconduction-centre-cout',
                            'order'    => 30,
                            'resource' => PrivilegeController::getResourceId(OffreFormationController::class, 'reconductionCentreCout'),
                        ],
                        'reconduction-modulateur'  => [
                            'label'    => 'Reconduction des modulateurs',
                            'title'    => 'Reconduction des modulateurs de l\'offre de formation',
                            'route'    => 'aof/reconduction-modulateur',
                            'order'    => 40,
                            'resource' => PrivilegeController::getResourceId(OffreFormationController::class, 'reconductionModulateur'),
                        ],

                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        /* Global */
        [
            'controller' => OffreFormationController::class,
            'action'     => ['index', 'administrationOffre'],
            'privileges' => Privileges::ODF_VISUALISATION,
        ],
        [
            'controller' => OffreFormationController::class,
            'action'     => ['export'],
            'privileges' => Privileges::ODF_EXPORT_CSV,
        ],
        [
            'controller' => OffreFormationController::class,
            'action'     => ['reconduction'],
            'privileges' => Privileges::ODF_RECONDUCTION_OFFRE,
        ],
        [
            'controller' => OffreFormationController::class,
            'action'     => ['reconductionCentreCout'],
            'privileges' => Privileges::ODF_RECONDUCTION_CENTRE_COUT,
        ],
        [
            'controller' => OffreFormationController::class,
            'action'     => ['reconductionModulateur'],
            'privileges' => Privileges::ODF_RECONDUCTION_MODULATEUR,
        ],

        /* Etapes */
        [
            'controller' => EtapeController::class,
            'action'     => ['voir', 'search'],
            'privileges' => Privileges::ODF_ETAPE_VISUALISATION,
        ],
        [
            'controller' => EtapeController::class,
            'action'     => ['restaurer', 'saisir', 'supprimer'],
            'privileges' => Privileges::ODF_ETAPE_EDITION,
        ],
        /* Éléments pédagogiques */
        [
            'controller' => ElementPedagogiqueController::class,
            'action'     => ['voir'], // getPeriode est utilisé pour la saisie de service!!!
            'privileges' => Privileges::ODF_ELEMENT_VISUALISATION,
        ],
        [
            'controller' => ElementPedagogiqueController::class,
            'action'     => ['search', 'getPeriode'], // getPeriode est utilisé pour la saisie de service!!!
            'privileges' => [
                Privileges::ODF_ELEMENT_VISUALISATION,
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
            ],
        ],
        [
            'controller' => ElementPedagogiqueController::class,
            'action'     => ['volume-horaire'],
            'privileges' => [
                Privileges::ODF_ELEMENT_VH_VISUALISATION,
                Privileges::ODF_ELEMENT_VH_EDITION,
            ],

        ],
        [
            'controller' => ElementPedagogiqueController::class,
            'action'     => ['modulateurs-centres-couts'],
            'privileges' => [
                Privileges::ODF_CENTRES_COUT_EDITION,
                Privileges::ODF_MODULATEURS_EDITION,
            ],

        ],
        [
            'controller' => ElementPedagogiqueController::class,
            'action'     => ['saisir', 'supprimer'],
            'privileges' => Privileges::ODF_ELEMENT_EDITION,
        ],
        [
            'controller' => ElementPedagogiqueController::class,
            'action'     => ['synchronisation', 'synchronisation-par-code'],
            'privileges' => Privileges::ODF_ELEMENT_SYNCHRONISATION,
        ],
        /* Modulateurs */
        [
            'controller' => ModulateurController::class,
            'action'     => ['saisir'],
            'privileges' => Privileges::ODF_MODULATEURS_EDITION,
        ],
        /* Centres de coûts */
        [
            'controller' => EtapeCentreCoutController::class,
            'action'     => ['saisir'],
            'privileges' => Privileges::ODF_CENTRES_COUT_EDITION,
        ],
        /* Taux de mixité */
        [
            'controller' => EtapeController::class,
            'action'     => ['taux-mixite'],
            'privileges' => Privileges::ODF_TAUX_MIXITE_EDITION,
        ],
    ],

    'rules'        => [
        [
            'privileges' => Privileges::ODF_ELEMENT_EDITION,
            'resources'  => ['ElementPedagogique', 'Structure'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_ETAPE_EDITION,
            'resources'  => ['Etape', 'Structure'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_CENTRES_COUT_EDITION,
            'resources'  => ['Etape', 'Structure', 'ElementPedagogique', 'CentreCoutEp'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_MODULATEURS_EDITION,
            'resources'  => ['Etape', 'Structure', 'ElementPedagogique', 'ElementModulateur'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_TAUX_MIXITE_EDITION,
            'resources'  => ['Etape', 'Structure', 'ElementPedagogique'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_ELEMENT_VH_EDITION,
            'resources'  => ['Etape', 'Structure', 'ElementPedagogique', 'VolumeHoraireEns', 'TypeIntervention'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_ELEMENT_SYNCHRONISATION,
            'resources'  => ['ElementPedagogique'],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],

    ],
    'controllers'  => [
        Controller\EtapeController::class           => Controller\Factory\EtapeControllerFactory::class,
        Controller\ModulateurController::class      => Controller\Factory\ModulateurControllerFactory::class,
        Controller\EtapeCentreCoutController::class => Controller\Factory\EtapeCentreCoutControllerFactory::class,
        OffreFormationController::class             => Controller\Factory\OffreFormationControllerFactory::class,
        ElementPedagogiqueController::class         => Controller\Factory\ElementPedagogiqueControllerFactory::class,
    ],
    'services'     => [
        Service\ElementPedagogiqueService::class   => Service\Factory\ElementPedagogiqueServiceFactory::class,
        Service\CheminPedagogiqueService::class    => Service\Factory\CheminPedagogiqueServiceFactory::class,
        Service\EtapeService::class                => Service\Factory\EtapeServiceFactory::class,
        Service\TypeFormationService::class        => Service\Factory\TypeFormationServiceFactory::class,
        Service\GroupeTypeFormationService::class  => Service\Factory\GroupeTypeFormationServiceFactory::class,
        Service\NiveauEtapeService::class          => Service\Factory\NiveauEtapeServiceFactory::class,
        Service\NiveauFormationService::class      => Service\Factory\NiveauFormationServiceFactory::class,
        Service\ElementModulateurService::class    => Service\Factory\ElementModulateurServiceFactory::class,
        Service\DomaineFonctionnelService::class   => Service\Factory\DomaineFonctionnelServiceFactory::class,
        Service\OffreFormationService::class       => Service\Factory\OffreFormationServiceFactory::class,
        Assertion\OffreDeFormationAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        Service\VolumeHoraireEnsService::class     => Service\Factory\VolumeHoraireEnsServiceFactory::class,
        Processus\ReconductionProcessus::class     => Processus\Factory\ReconductionProcessusFactory::class,
    ],
    'forms'        => [

        Form\ElementPedagogiqueRechercheFieldset::class       => Form\Factory\ElementPedagogiqueRechercheFieldsetFactory::class,
        Form\EtapeSaisie::class                               => Form\Factory\EtapeSaisieFactory::class,
        Form\ElementPedagogiqueSaisie::class                  => Form\Factory\ElementPedagogiqueSaisieFactory::class,
        Form\ElementPedagogiqueSynchronisationForm::class     => Form\Factory\ElementPedagogiqueSynchronisationFormFactory::class,
        Form\EtapeModulateursSaisie::class                    => Form\Factory\EtapeModulateursSaisieFactory::class,
        Form\ElementModulateursFieldset::class                => Form\Factory\ElementModulateursFieldsetFactory::class,
        Form\EtapeCentreCout\EtapeCentreCoutForm::class       => Form\EtapeCentreCout\EtapeCentreCoutFormFactory::class,
        Form\EtapeCentreCout\ElementCentreCoutFieldset::class => Form\EtapeCentreCout\ElementCentreCoutFieldsetFactory::class,
        Form\TauxMixite\TauxMixiteForm::class                 => Form\TauxMixite\TauxMixiteFormFactory::class,
        Form\TauxMixite\TauxMixiteFieldset::class             => Form\TauxMixite\TauxMixiteFieldsetFactory::class,
    ],
    'view_helpers' => [
        'etape'                               => View\Helper\Factory\EtapeViewHelperFactory::class,
        'etapeModulateursSaisieForm'          => View\Helper\Factory\EtapeModulateursSaisieFormFactory::class,
        'elementModulateursSaisieFieldset'    => View\Helper\Factory\ElementModulateursSaisieFieldsetFactory::class,
        'etapeCentreCoutForm'                 => View\Helper\Factory\EtapeCentreCoutFormViewHelperFactory::class,
        'elementCentreCoutFieldset'           => View\Helper\Factory\ElementCentreCoutFieldsetViewHelperFactory::class,
        'etapeTauxMixiteForm'                 => View\Helper\Factory\EtapeTauxMixiteFormViewHelperFactory::class,
        'elementTauxMixiteFieldset'           => View\Helper\Factory\ElementTauxMixiteFieldsetViewHelperFactory::class,
        'fieldsetElementPedagogiqueRecherche' => View\Helper\Factory\FieldsetElementPedagogiqueRechercheFactory::class,
        'elementPedagogique'                  => View\Helper\Factory\ElementPedagogiqueViewHelperFactory::class,
    ],
];