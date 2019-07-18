<?php

return [
    [
        'TBL_NAME'           => 'chargens_seuils_def',
        'TABLE_NAME'         => 'TBL_CHARGENS_SEUILS_DEF',
        'VIEW_NAME'          => 'V_TBL_CHARGENS_SEUILS_DEF',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_CHARGENS_SEUILS_DEF__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 1,
        'FEUILLE_DE_ROUTE'   => false,
    ],
    [
        'TBL_NAME'           => 'formule',
        'TABLE_NAME'         => null,
        'VIEW_NAME'          => null,
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => null,
        'CUSTOM_CALCUL_PROC' => 'OSE_FORMULE.CALCULER_TBL',
        'ORDRE'              => 1,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'chargens',
        'TABLE_NAME'         => 'TBL_CHARGENS',
        'VIEW_NAME'          => 'V_TBL_CHARGENS',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_CHARGENS__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 1,
        'FEUILLE_DE_ROUTE'   => false,
    ],
    [
        'TBL_NAME'           => 'dmep_liquidation',
        'TABLE_NAME'         => 'TBL_DMEP_LIQUIDATION',
        'VIEW_NAME'          => 'V_TBL_DMEP_LIQUIDATION',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_DMEP_LIQUIDATION__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 1,
        'FEUILLE_DE_ROUTE'   => false,
    ],
    [
        'TBL_NAME'           => 'piece_jointe_demande',
        'TABLE_NAME'         => 'TBL_PIECE_JOINTE_DEMANDE',
        'VIEW_NAME'          => 'V_TBL_PIECE_JOINTE_DEMANDE',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_PIECE_JOINTE_DEMANDE__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 2,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'piece_jointe_fournie',
        'TABLE_NAME'         => 'TBL_PIECE_JOINTE_FOURNIE',
        'VIEW_NAME'          => 'V_TBL_PIECE_JOINTE_FOURNIE',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_PIECE_JOINTE_FOURNIE__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 3,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'agrement',
        'TABLE_NAME'         => 'TBL_AGREMENT',
        'VIEW_NAME'          => 'V_TBL_AGREMENT',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_AGREMENT__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 4,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'cloture_realise',
        'TABLE_NAME'         => 'TBL_CLOTURE_REALISE',
        'VIEW_NAME'          => 'V_TBL_CLOTURE_REALISE',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_CLOTURE_REALISE__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 5,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'contrat',
        'TABLE_NAME'         => 'TBL_CONTRAT',
        'VIEW_NAME'          => 'V_TBL_CONTRAT',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_CONTRAT__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 6,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'dossier',
        'TABLE_NAME'         => 'TBL_DOSSIER',
        'VIEW_NAME'          => 'V_TBL_DOSSIER',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_DOSSIER__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 7,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'paiement',
        'TABLE_NAME'         => 'TBL_PAIEMENT',
        'VIEW_NAME'          => 'V_TBL_PAIEMENT',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_PAIEMENT__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 8,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'piece_jointe',
        'TABLE_NAME'         => 'TBL_PIECE_JOINTE',
        'VIEW_NAME'          => 'V_TBL_PIECE_JOINTE',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_PIECE_JOINTE__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 9,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'service_saisie',
        'TABLE_NAME'         => 'TBL_SERVICE_SAISIE',
        'VIEW_NAME'          => 'V_TBL_SERVICE_SAISIE',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_SERVICE_SAISIE__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 10,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'service_referentiel',
        'TABLE_NAME'         => 'TBL_SERVICE_REFERENTIEL',
        'VIEW_NAME'          => 'V_TBL_SERVICE_REFERENTIEL',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_SERVICE_REFERENTIEL__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 11,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'validation_enseignement',
        'TABLE_NAME'         => 'TBL_VALIDATION_ENSEIGNEMENT',
        'VIEW_NAME'          => 'V_TBL_VALIDATION_ENSEIGNEMENT',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_VALIDATION_ENSEIGNEMENT_UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 12,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'validation_referentiel',
        'TABLE_NAME'         => 'TBL_VALIDATION_REFERENTIEL',
        'VIEW_NAME'          => 'V_TBL_VALIDATION_REFERENTIEL',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_VALIDATION_REFERENTIEL__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 13,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'service',
        'TABLE_NAME'         => 'TBL_SERVICE',
        'VIEW_NAME'          => 'V_TBL_SERVICE',
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => 'TBL_SERVICE__UN',
        'CUSTOM_CALCUL_PROC' => null,
        'ORDRE'              => 14,
        'FEUILLE_DE_ROUTE'   => true,
    ],
    [
        'TBL_NAME'           => 'workflow',
        'TABLE_NAME'         => null,
        'VIEW_NAME'          => null,
        'SEQUENCE_NAME'      => null,
        'CONSTRAINT_NAME'    => null,
        'CUSTOM_CALCUL_PROC' => 'OSE_WORKFLOW.CALCULER_TBL',
        'ORDRE'              => 15,
        'FEUILLE_DE_ROUTE'   => true,
    ],
];