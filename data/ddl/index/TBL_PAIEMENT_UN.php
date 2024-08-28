<?php

//@formatter:off

return [
    'name'    => 'TBL_PAIEMENT_UN',
    'unique'  => TRUE,
    'table'   => 'TBL_PAIEMENT',
    'columns' => [
        'INTERVENANT_ID',
        'MISE_EN_PAIEMENT_ID',
        'SERVICE_ID',
        'SERVICE_REFERENTIEL_ID',
        'MISSION_ID',
        'TAUX_REMU_ID',
        'TAUX_HORAIRE',
        'PERIODE_ENS_ID',
        'TYPE_HEURES_ID',
    ],
];

//@formatter:on
