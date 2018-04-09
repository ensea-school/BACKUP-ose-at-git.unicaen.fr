<?php

namespace Application;

return [

    /* Déclaration du contrôleur */
    'controllers'     => [
        'factories' => [
            'Application\Controller\Formule' => Controller\Factory\FormuleControllerFactory::class,
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'formule-calcul' => [
                    'options' => [
                        'route'    => 'formule-calcul',
                        'defaults' => [
                            'controller' => 'Application\Controller\Formule',
                            'action'     => 'calculer-tout',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            Service\FormuleIntervenantService::class                      => Service\FormuleIntervenantService::class,
            Service\FormuleServiceModifieService::class                   => Service\FormuleServiceModifieService::class,
            Service\FormuleServiceService::class                          => Service\FormuleServiceService::class,
            Service\FormuleServiceReferentielService::class               => Service\FormuleServiceReferentielService::class,
            Service\FormuleVolumeHoraireService::class                    => Service\FormuleVolumeHoraireService::class,
            Service\FormuleVolumeHoraireReferentielService::class         => Service\FormuleVolumeHoraireReferentielService::class,
            Service\FormuleResultatService::class                         => Service\FormuleResultatService::class,
            Service\FormuleResultatServiceService::class                  => Service\FormuleResultatServiceService::class,
            Service\FormuleResultatServiceReferentielService::class       => Service\FormuleResultatServiceReferentielService::class,
            Service\FormuleResultatVolumeHoraireService::class            => Service\FormuleResultatVolumeHoraireService::class,
            Service\FormuleResultatVolumeHoraireReferentielService::class => Service\FormuleResultatVolumeHoraireReferentielService::class,
        ],
    ],
];