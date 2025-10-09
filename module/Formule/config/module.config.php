<?php

namespace Formule;

use Application\Provider\Privileges;

return [
    'routes' => [
        'formule' => [
            'route' => '/formule',
            /* Ne pas supprimer, les childs soont écrites dans les autres fichiers de conf !! */
        ],

        'intervenant' => [
            'child_routes' => [
                'details'             => [
                    'route'       => '/:intervenant/formule/details[/:typeVolumeHoraire/:etatVolumeHoraire]',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                        'etatVolumeHoraire' => '[0-9]*',
                    ],
                    'controller'  => Controller\AffichageController::class,
                    'action'      => 'details',
                    'privileges'  => [Privileges::INTERVENANT_CALCUL_HETD],
                    'assertion'   => Assertion\FormuleAssertion::class,
                ],
                'details-data'        => [
                    'route'       => '/:intervenant/formule/details-data[/:typeVolumeHoraire/:etatVolumeHoraire]',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                        'etatVolumeHoraire' => '[0-9]*',
                    ],
                    'controller'  => Controller\AffichageController::class,
                    'action'      => 'details-data',
                    'privileges'  => [Privileges::INTERVENANT_CALCUL_HETD],
                    'assertion'   => Assertion\FormuleAssertion::class,
                ],
                'formule-totaux-hetd' => [
                    'route'       => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                    ],
                    'controller'  => Controller\AffichageController::class,
                    'action'      => 'formule-totaux-hetd',
                    'privileges'  => [
                        Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                        Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                        Privileges::REFERENTIEL_PREVU_VISUALISATION,
                        Privileges::REFERENTIEL_REALISE_VISUALISATION,
                    ],
                ],
            ],
        ],
    ],


    'navigation' => [
        'intervenant' => [
            'pages' => [
                'details' => [
                    'label'        => "Calcul HETD",
                    'title'        => "Calcul des heures équivalent TD {id}",
                    'route'        => 'intervenant/details',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'action'       => 'details',
                    'withtarget'   => true,
                    'order'        => 3,
                ],
            ],
        ],
    ],


    'controllers' => [
        Controller\AffichageController::class => Controller\AffichageControllerFactory::class,
    ],


    'services' => [
        Service\FormuleService::class     => Service\FormuleServiceFactory::class,
        Tbl\Process\FormuleProcess::class => Tbl\Process\FormuleProcessFactory::class,
        Command\BuildCommand::class       => Command\BuildCommandFactory::class,
        Command\CalculCommand::class      => Command\CalculCommandFactory::class,
        Service\AfficheurService::class   => Service\AfficheurServiceFactory::class,
    ],


    'laminas-cli' => [
        'commands' => [
            'build-formules' => Command\BuildCommand::class,
            'formule-calcul' => Command\CalculCommand::class,
        ],
    ],
];