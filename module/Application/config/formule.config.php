<?php

namespace Application;

return [
    'service_manager' => [
        'invokables' => [
            'ApplicationFormuleIntervenant'                         => 'Application\\Service\\FormuleIntervenant',
            'ApplicationFormuleServiceModifie'                      => 'Application\\Service\\FormuleServiceModifie',
            'ApplicationFormuleService'                             => 'Application\\Service\\FormuleService',
            'ApplicationFormuleServiceReferentiel'                  => 'Application\\Service\\FormuleServiceReferentiel',
            'ApplicationFormuleVolumeHoraire'                       => 'Application\\Service\\FormuleVolumeHoraire',
            'ApplicationFormuleVolumeHoraireReferentiel'            => 'Application\\Service\\FormuleVolumeHoraireReferentiel',
            'ApplicationFormuleResultat'                            => 'Application\\Service\\FormuleResultat',
            'ApplicationFormuleResultatService'                     => 'Application\\Service\\FormuleResultatService',
            'ApplicationFormuleResultatServiceReferentiel'          => 'Application\\Service\\FormuleResultatServiceReferentiel',
            'ApplicationFormuleResultatVolumeHoraire'               => 'Application\\Service\\FormuleResultatVolumeHoraire',
            'ApplicationFormuleResultatVolumeHoraireReferentiel'    => 'Application\\Service\\FormuleResultatVolumeHoraireReferentiel',
        ],
    ],
];