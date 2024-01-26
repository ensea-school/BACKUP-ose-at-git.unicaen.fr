<?php

return [
    'explicit'          => true,
    'table'             => [
        'includes' => [

        ],
    ],
    'materialized-view' => [
        'includes' => [

        ],
        'excludes' => [
            //'MV_EXT_SERVICE',
        ],
    ],
    'view'              => [
        'includes'    => [
            'V_INDIC_ATT_VALID_ENS_AUTRE',
            'V_INDIC_ATT_VALID_REF_AUTRE',
            'V_INDIC_ATT_VALID_SERVICE',
            'V_INDIC_ATT_VALID_SERVICE_REF',
            'V_INDIC_ATTENTE_DEMANDE_MEP',
            'V_INDIC_ATTENTE_MEP',
            'V_INDIC_TOUS_SERVICES_VALIDES',
            'V_TOTAL_DEMANDE_MEP_STRUCTURE',
            'V_VALIDATION_MISE_EN_PAIEMENT',
        ], 'excludes' => [
            'V_TBL_PLAFOND_%', // Les vues plafonds sont générées et non créées à partir de la DDL
        ],
    ],
    'package'           => [
        'includes' => [

        ],
    ],
    'trigger'           => [
        'includes' => [

        ],
    ],
    'sequence'          => [
        'includes' => [

        ],
    ],
];