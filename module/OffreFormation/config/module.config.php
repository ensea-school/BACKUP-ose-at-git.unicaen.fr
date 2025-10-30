<?php

namespace OffreFormation;

use Application\Provider\Privileges;
use Lieu\Entity\Db\Structure;
use OffreFormation\Controller\DisciplineController;
use OffreFormation\Controller\ElementPedagogiqueController;
use OffreFormation\Controller\EtapeCentreCoutController;
use OffreFormation\Controller\EtapeController;
use OffreFormation\Controller\EtapeTauxRemuController;
use OffreFormation\Controller\ModulateurController;
use OffreFormation\Controller\OffreFormationController;
use OffreFormation\Controller\TypeFormationController;
use OffreFormation\Controller\TypeInterventionController;
use OffreFormation\Entity\Db\CentreCoutEp;
use OffreFormation\Entity\Db\ElementModulateur;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Entity\Db\TypeInterventionStructure;
use OffreFormation\Entity\Db\VolumeHoraireEns;

return [
    'routes' => [
        'aof'               => [
            'route'         => '/administration-offre',
            'may_terminate' => false,
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
        'of'                => [
            'route'         => '/offre-de-formation',
            'controller'    => OffreFormationController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'export' => [
                    'route'         => '/export',
                    'controller'  => OffreFormationController::class,
                    'action'      => 'export',
                ],
                'element' => [
                    'route'         => '/element',
                    'may_terminate' => false,
                    'child_routes'  => [
                        'voir'      => [
                            'route'       => '/voir/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'voir',
                        ],
                        'ajouter'   => [
                            'route'      => '/ajouter',
                            'controller' => ElementPedagogiqueController::class,
                            'action'     => 'saisir',
                        ],
                        'modifier'  => [
                            'route'       => '/modifier/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'saisir',
                        ],
                        'supprimer' => [
                            'route'       => '/supprimer/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'supprimer',
                        ],
                        'search'    => [
                            'route'      => '/search',
                            'controller' => ElementPedagogiqueController::class,
                            'action'     => 'search',
                        ],

                        'get-periode'                         => [
                            'route'       => '/get-periode/:elementPedagogique',
                            'constraints' => ['elementPedagogique' => '[0-9]*'],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'getPeriode',
                        ],
                        'volume-horaire'                      => [
                            'route'       => '/volume-horaire/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'volume-horaire',
                        ],
                        'synchronisation'                     => [
                            'route'       => '/synchronisation/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'synchronisation',
                        ],
                        'synchronisation-par-code'            => [
                            'route'      => '/synchronisation-par-code',
                            'controller' => ElementPedagogiqueController::class,
                            'action'     => 'synchronisation-par-code',
                        ],
                        'modulateurs-centres-couts-taux-remu' => [
                            'route'       => '/modulateurs-centres-couts-taux-remu/:elementPedagogique',
                            'constraints' => [
                                'elementPedagogique' => '[0-9]*',
                            ],
                            'controller'  => ElementPedagogiqueController::class,
                            'action'      => 'modulateurs-centres-couts-taux-remu',
                        ],
                    ],
                ],
                'etape'   => [
                    'route'         => '/etape',
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
                        'taux-remus'    => [
                            'route'       => '/taux-remus/:etape',
                            'constraints' => [
                                'etape' => '[0-9]*',
                            ],
                            'controller'  => EtapeTauxRemuController::class,
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
        'discipline'        => [
            'route'         => '/discipline',
            'controller'    => DisciplineController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'voir'      => [
                    'route'       => '/voir/:discipline',
                    'constraints' => [
                        'discipline' => '[0-9]*',
                    ],
                    'action'      => 'voir',
                ],
                'saisir'    => [
                    'route'       => '/saisir[/:discipline]',
                    'constraints' => [
                        'discipline' => '[0-9]*',
                    ],
                    'action'      => 'saisir',
                ],
                'supprimer' => [
                    'route'       => '/supprimer/:discipline',
                    'constraints' => [
                        'discipline' => '[0-9]*',
                    ],
                    'action'      => 'supprimer',
                ],
            ],
        ],
        'type-intervention' => [
            'route'         => '/type-intervention',
            'controller'    => TypeInterventionController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'                             => [
                    'route'       => '/saisie[/:typeIntervention]',
                    'constraints' => [
                        'typeIntervention' => '[0-9]*',
                    ],
                    'action'      => 'saisie',
                ],
                'statut'                             => [
                    'route'       => '/statut/:typeIntervention',
                    'constraints' => [
                        'typeIntervention' => '[0-9]*',
                    ],
                    'action'      => 'statut',
                ],
                'delete'                             => [
                    'route'       => '/delete/:typeIntervention',
                    'constraints' => [
                        'typeIntervention' => '[0-9]*',
                    ],
                    'action'      => 'delete',
                ],
                'type-intervention-trier'            => [
                    'route'  => '/type-intervention-trier',
                    'action' => 'type-intervention-trier',
                ],
                'type-intervention-structure-saisie' => [
                    'route'       => '/type-intervention-structure-saisie/:typeIntervention[/:typeInterventionStructure]',
                    'constraints' => [
                        'typeIntervention'          => '[0-9]*',
                        'typeInterventionStructure' => '[0-9]*',
                    ],
                    'action'      => 'type-intervention-structure-saisie',
                ],
                'type-intervention-structure-delete' => [
                    'route'       => '/type-intervention-structure-delete/:typeInterventionStructure',
                    'constraints' => [
                        'typeInterventionStructure' => '[0-9]*',
                    ],
                    'action'      => 'type-intervention-structure-delete',
                ],
                'statut-saisie'                      => [
                    'route'       => '/statut-saisie/:typeIntervention[/:typeInterventionStatut]',
                    'constraints' => [
                        'typeIntervention'       => '[0-9]*',
                        'typeInterventionStatut' => '[0-9]*',
                    ],
                    'action'      => 'statut-saisie',
                ],
                'statut-delete'                      => [
                    'route'       => '/statut-delete/:typeIntervention/:typeInterventionStatut',
                    'constraints' => [
                        'typeInterventionStatut' => '[0-9]*',
                    ],
                    'action'      => 'statut-delete',
                ],
            ],
        ],
        'type-formation'    => [
            'route'         => '/type-formation',
            'controller'    => TypeFormationController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'           => [
                    'route'       => '/saisie/:typeFormation',
                    'constraints' => [
                        'typeFormation' => '[0-9]*',
                    ],
                    'action'      => 'saisie',
                ],
                'ajout'            => [
                    'route'       => '/ajout/:groupeTypeFormation',
                    'constraints' => [
                        'typeFormation' => '[0-9]*',
                    ],
                    'action'      => 'ajout',
                ],
                'supprimer'        => [
                    'route'       => '/supprimer/:typeFormation',
                    'constraints' => [
                        'typeFormation' => '[0-9]*',
                    ],
                    'action'      => 'supprimer',
                ],
                'saisie-groupe'    => [
                    'route'       => '/saisie-groupe[/:groupeTypeFormation]',
                    'constraints' => [
                        'groupeTypeFormation' => '[0-9]*',
                    ],
                    'action'      => 'saisieGroupe',
                ],
                'supprimer-groupe' => [
                    'route'       => '/supprimer-groupe/:groupeTypeFormation',
                    'constraints' => [
                        'groupeTypeFormation' => '[0-9]*',
                    ],
                    'action'      => 'supprimerGroupe',
                ],
                'trier'            => [
                    'route'  => '/trier/',
                    'action' => 'trier',
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
                        ],
                        'reconduction-centre-cout' => [
                            'label'    => 'Reconduction des centres de coûts',
                            'title'    => 'Reconduction des centres de coûts de l\'offre de formation',
                            'route'    => 'aof/reconduction-centre-cout',
                            'order'    => 30,
                        ],
                        'reconduction-modulateur'  => [
                            'label'    => 'Reconduction des modulateurs',
                            'title'    => 'Reconduction des modulateurs de l\'offre de formation',
                            'route'    => 'aof/reconduction-modulateur',
                            'order'    => 40,
                        ],
                        'discipline'               => [
                            'color'    => '#9F491F',
                            'label'    => "Types de disciplines",
                            'title'    => "Gestion des disciplines",
                            'route'    => 'discipline',
                            'order'    => 50,
                        ],
                        'type-formation'           => [
                            'label'          => 'Types de formations',
                            'route'          => 'type-formation',
                            'order'          => 60,
                            'border - color' => '#111',
                        ],
                        'type-intervention'        => [
                            'label'    => 'Types d\'interventions',
                            'route'    => 'type-intervention',
                            'order'    => 70,
                            'color'    => '#71DFD7',
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
            'action'     => ['modulateurs-centres-couts-taux-remu'],
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
        /* Taux de remus */
        [
            'controller' => EtapeTauxRemuController::class,
            'action'     => ['saisir'],
            'privileges' => Privileges::ODF_CENTRES_COUT_EDITION,
        ],
        /* Taux de mixité */
        [
            'controller' => EtapeController::class,
            'action'     => ['taux-mixite'],
            'privileges' => Privileges::ODF_TAUX_MIXITE_EDITION,
        ],

        /* discipline */
        [
            'controller' => DisciplineController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::DISCIPLINE_GESTION,
            ],
        ],
        [
            'controller' => DisciplineController::class,
            'action'     => ['voir'],
            'privileges' => [
                Privileges::DISCIPLINE_VISUALISATION,
            ],
        ],
        [
            'controller' => DisciplineController::class,
            'action'     => ['saisir', 'supprimer'],
            'privileges' => [
                Privileges::DISCIPLINE_EDITION,
            ],
        ],

        /* type d'intervention */
        [
            'controller' => TypeInterventionController::class,
            'action'     => ['index', 'statut'],
            'privileges' => [Privileges::TYPE_INTERVENTION_VISUALISATION],
        ],
        [
            'controller' => TypeInterventionController::class,
            'action'     => ['saisie', 'delete', 'type-intervention-structure-saisie', 'type-intervention-structure-delete',
                'type-intervention-trier', 'statut-saisie', 'statut-delete'],
            'privileges' => [Privileges::TYPE_INTERVENTION_EDITION],
        ],

        /* type formation */
        [
            'controller' => TypeFormationController::class,
            'action'     => ['index'],
            'privileges' => [Privileges::ODF_TYPE_FORMATION_VISUALISATION],
        ],
        [
            'controller' => TypeFormationController::class,
            'action'     => ['saisie', 'ajout', 'supprimer', 'saisieGroupe', 'supprimerGroupe', "trier"],
            'privileges' => [Privileges::ODF_TYPE_FORMATION_EDITION],
        ],
    ],

    'rules'        => [
        [
            'privileges' => Privileges::ODF_ELEMENT_EDITION,
            'resources'  => [ElementPedagogique::class, Structure::class],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_ETAPE_EDITION,
            'resources'  => [Etape::class, Structure::class],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_CENTRES_COUT_EDITION,
            'resources'  => [Etape::class, Structure::class, ElementPedagogique::class, CentreCoutEp::class],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_MODULATEURS_EDITION,
            'resources'  => [Etape::class, Structure::class, ElementPedagogique::class, ElementModulateur::class],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_TAUX_MIXITE_EDITION,
            'resources'  => [Etape::class, Structure::class, ElementPedagogique::class],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_ELEMENT_VH_EDITION,
            'resources'  => [Etape::class, Structure::class, ElementPedagogique::class, VolumeHoraireEns::class, TypeIntervention::class],
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::ODF_ELEMENT_SYNCHRONISATION,
            'resources'  => ElementPedagogique::class,
            'assertion'  => Assertion\OffreDeFormationAssertion::class,
        ],
        [
            'privileges' => Privileges::TYPE_INTERVENTION_EDITION,
            'resources'  => TypeInterventionStructure::class,
            'assertion'  => Assertion\TypeInterventionAssertion::class,
        ],


    ],
    'controllers'  => [
        Controller\DisciplineController::class       => Controller\Factory\DisciplineControllerFactory::class,
        Controller\EtapeController::class            => Controller\Factory\EtapeControllerFactory::class,
        Controller\ModulateurController::class       => Controller\Factory\ModulateurControllerFactory::class,
        Controller\EtapeCentreCoutController::class  => Controller\Factory\EtapeCentreCoutControllerFactory::class,
        Controller\EtapeTauxRemuController::class    => Controller\Factory\EtapeTauxRemuControllerFactory::class,
        OffreFormationController::class              => Controller\Factory\OffreFormationControllerFactory::class,
        ElementPedagogiqueController::class          => Controller\Factory\ElementPedagogiqueControllerFactory::class,
        Controller\TypeInterventionController::class => Controller\Factory\TypeInterventionControllerFactory::class,
        TypeFormationController::class               => Controller\Factory\TypeFormationControllerFactory::class,
    ],
    'services'     => [
        Service\DisciplineService::class                => Service\Factory\DisciplineServiceFactory::class,
        Service\ElementPedagogiqueService::class        => Service\Factory\ElementPedagogiqueServiceFactory::class,
        Service\CheminPedagogiqueService::class         => Service\Factory\CheminPedagogiqueServiceFactory::class,
        Service\EtapeService::class                     => Service\Factory\EtapeServiceFactory::class,
        Service\TypeFormationService::class             => Service\Factory\TypeFormationServiceFactory::class,
        Service\GroupeTypeFormationService::class       => Service\Factory\GroupeTypeFormationServiceFactory::class,
        Service\NiveauEtapeService::class               => Service\Factory\NiveauEtapeServiceFactory::class,
        Service\NiveauFormationService::class           => Service\Factory\NiveauFormationServiceFactory::class,
        Service\ElementModulateurService::class         => Service\Factory\ElementModulateurServiceFactory::class,
        Service\OffreFormationService::class            => Service\Factory\OffreFormationServiceFactory::class,
        Service\VolumeHoraireEnsService::class          => Service\Factory\VolumeHoraireEnsServiceFactory::class,
        Processus\ReconductionProcessus::class          => Processus\Factory\ReconductionProcessusFactory::class,
        Service\TypeInterventionService::class          => Service\Factory\TypeInterventionServiceFactory::class,
        Service\TypeInterventionStructureService::class => Service\Factory\TypeInterventionStructureServiceFactory::class,
        Service\TypeInterventionStatutService::class    => Service\Factory\TypeInterventionStatutServiceFactory::class,
        Service\TypeHeuresService::class                => Service\TypeHeuresServiceFactory::class,
        Service\CentreCoutEpService::class              => Service\CentreCoutEpServiceFactory::class,
        Command\MajTauxMixiteCommand::class             => Command\MajTauxMixiteCommandFactory::class,
    ],
    'forms'        => [
        Form\DisciplineForm::class                                       => Form\Factory\DisciplineFormFactory::class,
        Form\ElementPedagogiqueRechercheFieldset::class                  => Form\Factory\ElementPedagogiqueRechercheFieldsetFactory::class,
        Form\EtapeSaisie::class                                          => Form\Factory\EtapeSaisieFactory::class,
        Form\ElementPedagogiqueSaisie::class                             => Form\Factory\ElementPedagogiqueSaisieFactory::class,
        Form\ElementPedagogiqueSynchronisationForm::class                => Form\Factory\ElementPedagogiqueSynchronisationFormFactory::class,
        Form\EtapeModulateursSaisie::class                               => Form\Factory\EtapeModulateursSaisieFactory::class,
        Form\ElementModulateursFieldset::class                           => Form\Factory\ElementModulateursFieldsetFactory::class,
        Form\EtapeCentreCout\EtapeCentreCoutForm::class                  => Form\EtapeCentreCout\EtapeCentreCoutFormFactory::class,
        Form\EtapeTauxRemu\EtapeTauxRemuForm::class                      => Form\EtapeTauxRemu\EtapeTauxRemuFormFactory::class,
        Form\EtapeCentreCout\ElementCentreCoutFieldset::class            => Form\EtapeCentreCout\ElementCentreCoutFieldsetFactory::class,
        Form\EtapeTauxRemu\ElementTauxRemuFieldset::class                => Form\EtapeTauxRemu\ElementTauxRemuFieldsetFactory::class,
        Form\TauxMixite\TauxMixiteForm::class                            => Form\TauxMixite\TauxMixiteFormFactory::class,
        Form\TauxMixite\TauxMixiteFieldset::class                        => Form\TauxMixite\TauxMixiteFieldsetFactory::class,
        Form\TypeIntervention\TypeInterventionSaisieForm::class          => Form\TypeIntervention\Factory\TypeInterventionSaisieFormFactory::class,
        Form\TypeIntervention\TypeInterventionStructureSaisieForm::class => Form\TypeIntervention\Factory\TypeInterventionStructureSaisieFormFactory::class,
        Form\TypeIntervention\TypeInterventionStatutSaisieForm::class    => Form\TypeIntervention\Factory\TypeInterventionStatutSaisieFormFactory::class,
        Form\TypeIntervention\TypeInterventionStatutDeleteForm::class    => Form\TypeIntervention\Factory\TypeInterventionStatutDeleteFormFactory::class,
        Form\TypeFormation\TypeFormationSaisieForm::class                => Form\TypeFormation\TypeFormationSaisieFormFactory::class,
        Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm::class    => Form\GroupeTypeFormation\GroupeTypeFormationSaisieFormFactory::class,
    ],
    'view_helpers' => [
        'etape'                               => View\Helper\Factory\EtapeViewHelperFactory::class,
        'etapeModulateursSaisieForm'          => View\Helper\Factory\EtapeModulateursSaisieFormFactory::class,
        'elementModulateursSaisieFieldset'    => View\Helper\Factory\ElementModulateursSaisieFieldsetFactory::class,
        'etapeCentreCoutForm'                 => View\Helper\Factory\EtapeCentreCoutFormViewHelperFactory::class,
        'etapeTauxRemuForm'                   => View\Helper\Factory\EtapeTauxRemuFormViewHelperFactory::class,
        'elementCentreCoutFieldset'           => View\Helper\Factory\ElementCentreCoutFieldsetViewHelperFactory::class,
        'elementTauxRemuFieldset'             => View\Helper\Factory\ElementTauxRemuFieldsetViewHelperFactory::class,
        'etapeTauxMixiteForm'                 => View\Helper\Factory\EtapeTauxMixiteFormViewHelperFactory::class,
        'elementTauxMixiteFieldset'           => View\Helper\Factory\ElementTauxMixiteFieldsetViewHelperFactory::class,
        'fieldsetElementPedagogiqueRecherche' => View\Helper\Factory\FieldsetElementPedagogiqueRechercheFactory::class,
        'elementPedagogique'                  => View\Helper\Factory\ElementPedagogiqueViewHelperFactory::class,
        'typeInterventionAdmin'               => View\Helper\Factory\TypeInterventionAdminViewHelperFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'maj-taux-mixite' => Command\MajTauxMixiteCommand::class,
        ],
    ],
];