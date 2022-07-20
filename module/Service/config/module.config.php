<?php

namespace Service;

use Application\Provider\Privilege\Privileges;
use Assert\Assertion;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Service\Controller\CampagneSaisieController;
use Service\Controller\CampagneSaisieControllerFactory;
use Service\Controller\RegleStructureValidationController;
use Service\Controller\RegleStructureValidationControllerFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'service' => [
            'child_routes' => [
                'horodatage' => [
                    'controller'  => ServiceController::class,
                    'route'       => '/horodatage/:intervenant/:typeVolumeHoraire/:referentiel',
                    'action'      => 'horodatage',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                        'referentiel'       => '[0-9]*',
                    ],
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
        'administration' => [
            'pages' => [
                'parametres' => [
                    'pages' => [
                        'campagnes-saisie' => [
                            'label'    => "Campagnes de saisie des services",
                            'route'    => 'parametres/campagnes-saisie',
                            'resource' => PrivilegeController::getResourceId(CampagneSaisieController::class, 'campagnes-saisie'),
                        ],
                    ],
                ],

                'gestion-intervenant' => [
                    'pages' => [
                        'regle-structure-validation' => [
                            'label'      => "Règles de validation enseignements par type d'intervenant",
                            'title'      => "Permet de définir les priorités de validation de volumes horaires par type d'intervenant",
                            'route'      => 'parametres/regle-structure-validation',
                            'withtarget' => true,
                            'order'      => 93,
                            'resource'   => PrivilegeController::getResourceId(RegleStructureValidationController::class, 'index'),
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
            //'assertion'  => Assertion\ClotureAssertion::class,
        ],
    ],

    'guards' => [
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
            //'assertion'  => Assertion\ServiceAssertion::class,
        ],
    ],


    'controllers' => [
        CampagneSaisieController::class           => CampagneSaisieControllerFactory::class,
        RegleStructureValidationController::class => RegleStructureValidationControllerFactory::class,
    ],

    'services' => [
        Service\EtatVolumeHoraireService::class        => InvokableFactory::class,
        Service\TypeVolumeHoraireService::class        => InvokableFactory::class,
        Service\CampagneSaisieService::class           => InvokableFactory::class,
        Service\RegleStructureValidationService::class => InvokableFactory::class,
        Assertion\ClotureAssertion::class              => Assertion\ClotureAssertionFactory::class,
    ],


    'forms' => [
        Form\CampagneSaisieForm::class => InvokableFactory::class,
    ],
];