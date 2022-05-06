<?php

return [
    'explicit'          => true,
    'table'             => [
        'includes' => [
            'TBL_DEMS',
            'TMP_SCENARIO_NOEUD_EFFECTIF',
            'DOSSIER_CHAMP_AUTRE_PAR_STATUT',
            'TBL_SERVICE_REFERENTIEL',
            'TBL_SERVICE_SAISIE',
            'STATUT_PRIVILEGE',
            'TYPE_AGREMENT_STATUT',
            'PLAFOND_APPLICATION',
        ],
    ],
    'materialized-view' => [
        'includes' => [
            'TBL_NOEUD',
        ],
        'excludes' => [
            //'MV_EXT_SERVICE',
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
            'V_INDICATEUR_520',
            'V_INDICATEUR_530',
            'V_INDICATEUR_540',
            'V_INDICATEUR_550',
            'V_INDICATEUR_560',
            'V_INDICATEUR_570',
            'V_INDICATEUR_580',
            'V_INDICATEUR_590',
            'V_INDICATEUR_680',
            'V_INDICATEUR_690',
            'V_INDICATEUR_1210',
            'V_INDICATEUR_1211',
            'V_INDICATEUR_1220',
            'V_INDICATEUR_1221',
            'V_INDICATEUR_1230',
            'V_INDICATEUR_1240',
            'V_TBL_SERVICE_REFERENTIEL',
            'V_TBL_SERVICE_SAISIE',
            'V_PRIVILEGES_ROLES',
            'V_CTL_SERVICES_ODF_HISTO',
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
            'F_STATUT_INTERVENANT',
            'F_STATUT_INTERVENANT_S',
        ],
    ],
    'sequence'          => [
        'includes' => [
            'TBL_SERVICE_SAISIE_ID_SEQ',
            'TBL_SERVICE_REFERENTIEL_ID_SEQ',
            'STATUT_PRIVILEGE_ID_SEQ',
            'TYPE_AGREMENT_STATUT_ID_SEQ',
            'TBL_NOEUD_ID_SEQ',
        ],
    ],
];