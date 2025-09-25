<?php

namespace Service;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Service\Controller\CampagneSaisieController;
use Service\Controller\RegleStructureValidationController;
use Service\Controller\ServiceController;
use Framework\Authorize\AssertionFactory;
use Workflow\Entity\Db\WorkflowEtape;


return [
    'routes' => [
        'service' => [
            'route'         => '/service',
            'controller'    => ServiceController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'resume'     => [
                    'route'  => '/resume',
                    'action' => 'resume',
                ],
                'export-csv' => [
                    'route'  => '/export-csv',
                    'action' => 'export-csv',
                ],
                'export-pdf' => [
                    'route'  => '/export-pdf',
                    'action' => 'export-pdf',
                ],
                'horodatage' => [
                    'route'       => '/horodatage/:intervenant/:typeVolumeHoraire/:referentiel',
                    'action'      => 'horodatage',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                        'referentiel'       => '[0-1]',
                    ],
                ],
            ],
        ],

        'intervenant' => [
            'child_routes' => [
                'services-prevus'   => [
                    'route'      => '/:intervenant/services-prevus',
                    'controller' => ServiceController::class,
                    'action'     => 'intervenant-saisie-prevu',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'PREVU',
                    ],
                ],
                'services-realises' => [
                    'route'      => '/:intervenant/services-realises',
                    'controller' => ServiceController::class,
                    'action'     => 'intervenant-saisie-realise',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'REALISE',
                    ],
                ],
                'cloturer'          => [
                    'route'      => '/:intervenant/cloturer',
                    'controller' => ServiceController::class,
                    'action'     => 'intervenant-cloture',
                ],
            ],
        ],

        'parametres' => [
            'child_routes' => [
                'campagnes-saisie' => [
                    'route'      => '/campagnes-saisie',
                    'controller' => CampagneSaisieController::class,
                    'action'     => 'campagnes-saisie',
                ],

                'regle-structure-validation' => [
                    'route'         => '/regle-structure-validation',
                    'controller'    => RegleStructureValidationController::class,
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        'delete' => [
                            'route'       => '/delete/:regleStructureValidation',
                            'action'      => 'delete',
                            'constraints' => [
                                'regleStructureValidation' => '[0-9]*',
                            ],
                        ],
                        'saisie' => [
                            'route'       => '/saisie/[:regleStructureValidation]',
                            'action'      => 'saisie',
                            'constraints' => [
                                'regleStructureValidation' => '[0-9]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'service' => [
            'label'    => 'Services',
            'title'    => "Visualisation et export des services",
            'order'    => 3,
            'route'    => 'service',
            'resource' => Authorize::controllerResource(ServiceController::class, 'index'),
        ],

        'intervenant' => [
            'pages' => [
                'services-prevus'   => [
                    'label'               => "Enseignements prévisionnels",
                    'title'               => "Enseignements prévisionnels de l'intervenant",
                    'route'               => 'intervenant/services-prevus',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WorkflowEtape::ENSEIGNEMENT_SAISIE.','.WorkflowEtape::REFERENTIEL_SAISIE,
                    'withtarget'          => true,
                    'resource'            => Authorize::controllerResource(ServiceController::class, 'intervenant-saisie-prevu'),
                    'visible'             => Assertion\ServiceAssertion::class,
                    'order'               => 6,
                ],
                'services-realises' => [
                    'label'               => "Enseignements réalisés",
                    'title'               => "Constatation des enseignements réalisés",
                    'route'               => 'intervenant/services-realises',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE.','.WorkflowEtape::REFERENTIEL_SAISIE_REALISE,
                    'withtarget'          => true,
                    'resource'            => Authorize::controllerResource(ServiceController::class, 'intervenant-saisie-realise'),
                    'visible'             => Assertion\ServiceAssertion::class,
                    'order'               => 13,
                ],
            ],
        ],

        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'campagnes-saisie' => [
                            'label'    => "Campagnes de saisie des services",
                            'route'    => 'parametres/campagnes-saisie',
                            'order'    => 20,
                            'resource' => Authorize::controllerResource(CampagneSaisieController::class, 'campagnes-saisie'),
                        ],
                    ],
                ],

                'intervenants' => [
                    'pages' => [
                        'regle-structure-validation' => [
                            'label'      => "Règles de validation des enseignements",
                            'title'      => "Permet de définir les priorités de validation de volumes horaires par type d'intervenant",
                            'route'      => 'parametres/regle-structure-validation',
                            'withtarget' => true,
                            'order'      => 30,
                            'resource'   => Authorize::controllerResource(RegleStructureValidationController::class, 'index'),
                        ],
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::CLOTURE_CLOTURE,
                Privileges::CLOTURE_REOUVERTURE,
            ],
            'resources'  => ['Validation', 'Intervenant'],
            'assertion'  => Assertion\ClotureAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => ServiceController::class,
            'action'     => ['index', 'resume'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['export-csv'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_EXPORT_CSV,
            ],
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['export-pdf'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_EXPORT_PDF,
            ],
        ],
        [
            'controller' => CampagneSaisieController::class,
            'action'     => ['campagnes-saisie'],
            'privileges' => [
                Privileges::PARAMETRES_CAMPAGNES_SAISIE_VISUALISATION,
            ],
        ],
        [
            'controller' => RegleStructureValidationController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::PARAMETRES_REGLES_STRUCTURE_VALIDATION_VISUALISATION,
            ],
        ],
        [
            'controller' => RegleStructureValidationController::class,
            'action'     => ['saisie', 'delete'],
            'privileges' => [
                Privileges::PARAMETRES_REGLES_STRUCTURE_VALIDATION_EDITION,
            ],
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['horodatage'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
            ],
            'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['intervenant-cloture'],
            'privileges' => [
                Privileges::CLOTURE_CLOTURE,
                Privileges::CLOTURE_REOUVERTURE,
            ],
            'assertion'  => Assertion\ClotureAssertion::class,
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['intervenant-cloture'],
            'roles'      => ['user'],
            'assertion'  => Assertion\ClotureAssertion::class,
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['intervenant-saisie-prevu'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                Privileges::REFERENTIEL_PREVU_VISUALISATION,
            ],
            'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => ServiceController::class,
            'action'     => ['intervenant-saisie-realise'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                Privileges::REFERENTIEL_REALISE_VISUALISATION,
            ],
            'assertion'  => Assertion\ServiceAssertion::class,
        ],
    ],


    'controllers' => [
        CampagneSaisieController::class           => Controller\CampagneSaisieControllerFactory::class,
        RegleStructureValidationController::class => Controller\RegleStructureValidationControllerFactory::class,
        ServiceController::class                  => InvokableFactory::class,
    ],

    'services' => [
        Service\TypeServiceService::class              => Service\TypeServiceServiceFactory::class,
        Service\EtatVolumeHoraireService::class        => InvokableFactory::class,
        Service\TypeVolumeHoraireService::class        => InvokableFactory::class,
        Service\CampagneSaisieService::class           => InvokableFactory::class,
        Service\RegleStructureValidationService::class => InvokableFactory::class,
        Service\ResumeService::class                   => InvokableFactory::class,
        Assertion\ClotureAssertion::class              => AssertionFactory::class,
        Assertion\ServiceAssertion::class              => AssertionFactory::class,
        Service\RechercheService::class                => InvokableFactory::class,
        Hydrator\RechercheHydrator::class              => InvokableFactory::class,
    ],


    'forms' => [
        Form\CampagneSaisieForm::class => InvokableFactory::class,
        Form\RechercheForm::class      => Form\RechercheFormFactory::class,
    ],

    'view_helpers' => [
        'horodatage'    => View\Helper\HorodatageViewHelperFactory::class,
        'serviceResume' => View\Helper\ResumeViewHelper::class,
    ],
];