<?php

namespace Application;

return [
    'service_manager' => [
        'invokables' => [
            'ApplicationFormuleIntervenant'                         => Service\FormuleIntervenant::class,
            'ApplicationFormuleServiceModifie'                      => Service\FormuleServiceModifie::class,
            'ApplicationFormuleService'                             => Service\FormuleServiceService::class,
            'ApplicationFormuleServiceReferentiel'                  => Service\FormuleServiceReferentiel::class,
            'ApplicationFormuleVolumeHoraire'                       => Service\FormuleVolumeHoraire::class,
            'ApplicationFormuleVolumeHoraireReferentiel'            => Service\FormuleVolumeHoraireReferentiel::class,
            'ApplicationFormuleResultat'                            => Service\FormuleResultat::class,
            'ApplicationFormuleResultatService'                     => Service\FormuleResultatServiceService::class,
            'ApplicationFormuleResultatServiceReferentiel'          => Service\FormuleResultatServiceReferentiel::class,
            'ApplicationFormuleResultatVolumeHoraire'               => Service\FormuleResultatVolumeHoraire::class,
            'ApplicationFormuleResultatVolumeHoraireReferentiel'    => Service\FormuleResultatVolumeHoraireReferentiel::class,
        ],
    ],
];