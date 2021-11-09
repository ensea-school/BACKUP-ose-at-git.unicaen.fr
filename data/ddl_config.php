<?php

return [
    'explicit'          => true,
    'table'             => [
        'includes' => [
            'ADRESSE_INTERVENANT',
            'ADRESSE_STRUCTURE',
            'INTERVENANT_SAISIE',
            'DOSSIER',
            'TBL_DEMS',
            'VERSION',
            'TMP_SCENARIO_NOEUD_EFFECTIF',
        ],
    ],
    'materialized-view' => [
        'includes' => [

        ],
    ],
    'view'              => [
        'includes'    => [
            'V_INDIC_DIFF_DOSSIER',
            'V_MEP_INTERVENANT_STRUCTURE',
            'V_CHARGENS_SEUILS_DED_DEF',
            'V_CHARGENS_CALC_EFFECTIF',
            'V_CHARGENS_GRANDS_LIENS',
            'V_CHARGENS_PRECALCUL_HEURES',
        ], 'excludes' => [
            'V_TBL_PLAFOND_%', // Les vues plafonds sont générées et non créées à partir de la DDL
        ],
    ],
    'package'           => [
        'includes' => [
            'FORMULE_ENSICAEN',
        ],
    ],
    'trigger'           => [
        'includes' => [
            'F_CONTRAT',
            'F_CONTRAT_S',
            'INDIC_TRG_MODIF_DOSSIER',
            'CHARGENS_MAJ_EFFECTIFS',
        ],
    ],
    'sequence'          => [
        'includes' => [

        ],
    ],
];