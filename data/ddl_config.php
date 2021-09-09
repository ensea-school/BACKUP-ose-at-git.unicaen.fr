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

    ]],
    'view'              => ['includes' => [
        'V_INDIC_DIFF_DOSSIER',
        'V_MEP_INTERVENANT_STRUCTURE',
    ], 'excludes'                      => [
        'V_TBL_PLAFOND_%', // Les vues plafonds sont générées et non créées à partir de la DDL
    ],],
    'package'           => ['includes' => [

    ]],
    'trigger'           => ['includes' => [
        'F_CONTRAT', 'F_CONTRAT_S', 'INDIC_TRG_MODIF_DOSSIER',
    ]],
    'sequence'          => ['includes' => [

    ]],
];