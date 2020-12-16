<?php

return [
    'explicit'          => true,
    'table'             => ['includes' => [
        'ADRESSE_INTERVENANT',
        'ADRESSE_STRUCTURE',
        'INTERVENANT_SAISIE',
        'DOSSIER',
        'TBL_DEMS',

    ]],
    'materialized-view' => ['includes' => [

    ], 'excludes'                      => ['MV_EXT_SERVICE']],
    'view'              => ['includes' => [
        'V_INDIC_DIFF_DOSSIER',

    ]],
    'package'           => ['includes' => [

    ]],
    'trigger'           => ['includes' => [
        'F_CONTRAT', 'F_CONTRAT_S', 'INDIC_TRG_MODIF_DOSSIER',
    ]],
    'sequence'          => ['includes' => [

    ]],
];