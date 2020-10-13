<?php

return [
    'explicit'          => true,
    'table'             => ['includes' => [
        'ADRESSE_INTERVENANT',
        'ADRESSE_STRUCTURE',
        'INTERVENANT_SAISIE',
        'DOSSIER',

    ]],
    'materialized-view' => ['includes' => [

    ], 'excludes'                      => ['MV_EXT_SERVICE']],
    'view'              => ['includes' => [

    ]],
    'package'           => ['includes' => [

    ]],
    'trigger'           => ['includes' => [
        'F_CONTRAT', 'F_CONTRAT_S', 'INDIC_TRG_MODIF_DOSSIER',
    ]],
    'sequence'          => ['includes' => [

    ]],
];