<?php

return [
    'explicit'          => true,
    'table'             => [
        'includes' => [
            'FORMULE_RESULTAT_VH',
            'FORMULE_RESULTAT_VH_REF',
            'FORMULE_RESULTAT_SERVICE',
            'FORMULE_RESULTAT_SERVICE_REF',
            'FORMULE_RESULTAT',
            'WF_ETAPE',
            'WF_ETAPE_DEP',
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
            'V_HAS_DMEP_A_FAIRE',
            'V_TBL_CONTRAT',
            'V_INDIC_ATT_VALID_ENS_AUTRE',
            'V_INDIC_ATT_VALID_REF_AUTRE',
            'V_INDIC_ATT_VALID_SERVICE',
            'V_INDIC_ATT_VALID_SERVICE_REF',
            'V_INDIC_ATTENTE_DEMANDE_MEP',
            'V_INDIC_ATTENTE_MEP',
            'V_INDIC_TOUS_SERVICES_VALIDES',
            'V_TOTAL_DEMANDE_MEP_STRUCTURE',
            'V_VALIDATION_MISE_EN_PAIEMENT',
            'V_FR_SERVICE_CENTRE_COUT',
            'V_FR_SERVICE_REF_CENTRE_COUT',
            'V_VOLUME_HORAIRE_ETAT',
            'V_VOLUME_HORAIRE_ETAT_MULTI',
            'V_VOLUME_HORAIRE_REF_ETAT',
            'V_VOLUME_HORAIRE_REF_ETAT_MULTI',
        ], 'excludes' => [
            'V_TBL_PLAFOND_%', // Les vues plafonds sont générées et non créées à partir de la DDL
        ],
    ],
    'package'           => [
        'includes' => [
            'OSE_EVENT',
            'OSE_HISTO',
            'OSE_PAIEMENT',
            'FORMULE_%',
            'OSE_FORMULE',
            'OSE_WORKFLOW',
        ],
    ],
    'trigger'           => [
        'includes' => [
            'MISE_EN_PAIEMENT_DEL_CK',
            'F_ELEMENT_MODULATEUR',
            'F_ELEMENT_MODULATEUR_S',
            'F_ELEMENT_PEDAGOGIQUE',
            'F_ELEMENT_PEDAGOGIQUE_S',
            'F_INTERVENANT',
            'F_INTERVENANT_S',
            'F_MOTIF_MODIFICATION_SERVICE',
            'F_MOTIF_MODIFICATION_SERVICE_S',
        ],
    ],
    'sequence'          => [
        'includes' => [

        ],
    ],
];