<?php

namespace Formule;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'formule' => [
            'route' => '/formule',
        ],

        'intervenant' => [
            'child_routes' => [
                'voir-heures-comp'    => [
                    'route'      => '/voir-heures-comp/:intervenant',
                    'controller' => Controller\AffichageController::class,
                    'action'     => 'voir-heures-comp',
                    'privileges' => [Privileges::INTERVENANT_CALCUL_HETD],
                    'assertion'  => Assertion\FormuleAssertion::class,
                ],
                'formule-totaux-hetd' => [
                    'route'       => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
                    'constraints' => [
                        'typeVolumeHoraire' => '[0-9]*',
                        'etatVolumeHoraire' => '[0-9]*',
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
                'voir-heures-comp' => [
                    'label'        => "Calcul HETD",
                    'title'        => "Calcul des heures équivalent TD {id}",
                    'route'        => 'intervenant/voir-heures-comp',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'action'       => 'voir-heures-comp',
                    'withtarget'   => true,
                    'resource'     => PrivilegeController::getResourceId(Controller\AffichageController::class, 'voir-heures-comp'),
                    'order'        => 3,
                ],
            ],
        ],
    ],


    'controllers' => [
        Controller\AffichageController::class => Controller\AffichageControllerFactory::class,
    ],


    'services' => [
        Assertion\FormuleAssertion::class         => AssertionFactory::class,
        Service\FormuleService::class             => Service\FormuleServiceFactory::class,
        Tbl\Process\FormuleProcess::class         => Tbl\Process\FormuleProcessFactory::class,
    ],
];