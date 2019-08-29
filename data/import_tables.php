<?php

return [
    "ADRESSE_INTERVENANT" => [
        "SYNC_FILTRE" => "WHERE INTERVENANT_ID IS NOT NULL",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 14
    ],
    "ADRESSE_STRUCTURE" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 5
    ],
    "AFFECTATION" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "SYNC_HOOK_BEFORE" => "UNICAEN_IMPORT.REFRESH_MV('MV_AFFECTATION');\n/* Import automatique des users des nouveaux directeurs */\nINSERT INTO utilisateur (\n  id, display_name, email, password, state, username\n)\nSELECT\n  utilisateur_id_seq.nextval id,\n  aff.*\nFROM\n  (SELECT DISTINCT display_name, email, password, state, username FROM mv_affectation) aff\nWHERE\n  username not in (select username from utilisateur);",
        "ORDRE" => 9
    ],
    "AFFECTATION_RECHERCHE" => [
        "SYNC_FILTRE" => "WHERE INTERVENANT_ID IS NOT NULL",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 13
    ],
    "CENTRE_COUT" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 7
    ],
    "CENTRE_COUT_EP" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "CENTRE_COUT_STRUCTURE" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 8
    ],
    "CHEMIN_PEDAGOGIQUE" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nJOIN element_pedagogique ep ON ep.id = element_pedagogique_id\r\nWHERE \r\n    ep.annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT \r\n    OR v_diff_chemin_pedagogique.source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 21
    ],
    "CORPS" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 10
    ],
    "DEPARTEMENT" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 2
    ],
    "DISCIPLINE" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "DOMAINE_FONCTIONNEL" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 6
    ],
    "EFFECTIFS" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nWHERE \r\n    annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT \r\n    OR source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 19
    ],
    "ELEMENT_PEDAGOGIQUE" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nWHERE \r\n    annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT \r\n    OR source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 18
    ],
    "ELEMENT_TAUX_REGIMES" => [
        "SYNC_FILTRE" => "WHERE IMPORT_ACTION IN ('delete','insert','undelete')",
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 20
    ],
    "ETABLISSEMENT" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 3
    ],
    "ETAPE" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nWHERE annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT OR source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 17
    ],
    "GRADE" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 11
    ],
    "GROUPE_TYPE_FORMATION" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 15
    ],
    "INTERVENANT" => [
        "SYNC_FILTRE" => "WHERE (\n    IMPORT_ACTION IN ('delete','update','undelete')\n    OR STATUT_ID IN (\n        SELECT si.id\n        FROM statut_intervenant si\n        JOIN type_intervenant ti ON ti.id = si.type_intervenant_id\n        WHERE ti.code = 'P'\n    )\n)",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "SYNC_HOOK_BEFORE" => "UNICAEN_IMPORT.REFRESH_MV('MV_UNICAEN_STRUCTURE_CODES');\nUNICAEN_IMPORT.REFRESH_MV('MV_INTERVENANT');",
        "ORDRE" => 12
    ],
    "LIEN" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nWHERE \r\n  SUBSTR(source_code,0,4) >= to_char(OSE_PARAMETRE.GET_ANNEE_IMPORT)\r\n  OR source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 24
    ],
    "NOEUD" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nWHERE \r\n    annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT \r\n    OR source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "SYNC_HOOK_AFTER" => "UNICAEN_IMPORT.REFRESH_MV('TBL_NOEUD');\r\nUNICAEN_TBL.CALCULER('chargens');",
        "ORDRE" => 23
    ],
    "PAYS" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 1
    ],
    "SCENARIO_LIEN" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nWHERE \r\n  SUBSTR(source_code,0,4) >= to_char(OSE_PARAMETRE.GET_ANNEE_IMPORT)\r\n  OR source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 25
    ],
    "SCENARIO_NOEUD" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "SERVICE" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "SERVICE_REFERENTIEL" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "STATUT_INTERVENANT" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "STRUCTURE" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 4
    ],
    "TYPE_DOTATION" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "TYPE_FORMATION" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 16
    ],
    "TYPE_INTERVENTION_EP" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 26
    ],
    "TYPE_MODULATEUR_EP" => [
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 27
    ],
    "VOLUME_HORAIRE" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "VOLUME_HORAIRE_CHARGE" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ],
    "VOLUME_HORAIRE_ENS" => [
        "SYNC_FILTRE" => "JOIN source ON source.code = 'FCAManager'\r\nJOIN element_pedagogique ep ON ep.id = element_pedagogique_id\r\nWHERE \r\n    ep.annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT \r\n    OR v_diff_volume_horaire_ens.source_id = source.id",
        "SYNC_ENABLED" => TRUE,
        "SYNC_JOB" => "synchro",
        "ORDRE" => 22
    ],
    "VOLUME_HORAIRE_REF" => [
        "SYNC_ENABLED" => FALSE,
        "ORDRE" => 0
    ]
];