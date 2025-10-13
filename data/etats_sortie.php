<?php

return [
    [
        'CODE'           => 'contrat',
        'LIBELLE'        => 'Contrat de travail',
        'PDF_TRAITEMENT' => '/data/Etats de sortie/contrat.php',
        'AUTO_BREAK'     => true,
        'REQUETE'        => 'SELECT * FROM v_contrat_main',
        'CLE'            => 'CONTRAT_ID',
        'BLOC1_NOM'      => 'serviceCode',
        'BLOC1_ZONE'     => 'table:table-row',
        'BLOC1_REQUETE'  => 'SELECT * FROM V_CONTRAT_SERVICES',
    ],
    [
        'CODE'           => 'preliquidation-siham',
        'LIBELLE'        => 'Préliquidation SIHAM',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_export_paiement_siham',
        'CSV_PARAMS'     => '{
    "ANNEE_ID": {
        "visible": false
    },
    "TYPE_INTERVENANT_ID": {
        "visible": false
    },
    "STRUCTURE_ID": {
        "visible": false
    },
    "STRUCTURE_IDS": {
        "visible": false
    },
    "PERIODE_ID": {
        "visible": false
    },
    "INTERVENANT_ID": {
        "visible": false
    },
    "TYPE": {
        "visible": false
    },
    "MATRICULE": {
        "libelle": "Matricule"
    },
     "CODE_INDEMNITE_RETENU": {
        "libelle": "Code indemnité/retenue"
    },
     "DU_MOIS": {
        "libelle": "Du mois (AAAA-MM)"
    },
     "ANNEE_DE_PAYE": {
        "libelle": "Année de paye (AA)"
    },
     "MOIS_DE_PAYE": {
        "libelle": "Mois de paye (MM)"
    },
    "NUMERO_DE_REMISE": {
        "libelle": "Numéro de remise"
    },
    "TG_SPECIFIQUE": {
        "libelle": "TG spécifique"
    },
    "DOSSIER_DE_PAYE": {
        "libelle": "Dossier de paye"
    },
    "DATE_PECUNIAIRE": {
        "libelle": "Date pécuniaire"
    },
    "NOMBRE_D_UNITES": {
        "libelle": "Nombre d’unités",
        "type": "float"
    },
    "MONTANT": {
        "libelle": "Montant",
        "type": "float"
    },
    "LIBELLE": {
        "libelle": "Libellé"
    },
    "MODE_DE_CALCUL": {
        "libelle": "Mode de calcul"
    },
    "CODE_ORIGINE": {
        "libelle": "Code origine"
    }
}',
    ],
    [
        'CODE'           => 'ecarts-heures-complementaire',
        'LIBELLE'        => 'Ecarts heures complémentaires',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_export_pilotage_ecarts_etats',
        'CSV_PARAMS'     => '{
    "INTERVENANT_TYPE_ID": {
        "visible": false
    },
    "STRUCTURE_ID": {
        "visible": false
    },
    "TYPE_HEURES_ID": {
        "visible": false
    },
    "INTERVENANT_ID": {
        "visible": false
    },
    "ANNEE_ID": {
        "visible": false
    },
    "ANNEE": {
        "libelle": "Année"
    },
    "ETAT": {
        "libelle": "Etat"
    },
    "TYPE_HEURES": {
        "libelle": "Type heures"
    },
    "STRUCTURE": {
        "libelle": "Structure"
    },
     "INTERVENANT_TYPE": {
        "libelle": "Type intervenant"
    },
     "INTERVENANT_CODE": {
        "libelle": "Code intervenant"
    },
     "NOM_USUEL": {
        "libelle": "Nom"
    },
    "PRENOM": {
        "libelle": "Prénom"
    },
     "HETD_PAYABLES": {
        "libelle": "HETD payables"
    }
}',
    ],
    [
        'CODE'           => 'winpaie',
        'LIBELLE'        => 'Extraction Winpaie',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_export_paiement_winpaie',
        'CSV_PARAMS'     => '{
    "ANNEE_ID": {
        "visible": false
    },
    "TYPE_INTERVENANT_ID": {
        "visible": false
    },
    "STRUCTURE_ID": {
        "visible": false
    },
    "STRUCTURE_IDS": {
        "visible": false
    },
    "PERIODE_ID": {
        "visible": false
    },
    "INTERVENANT_ID": {
        "visible": false
    },
    "INSEE": {
        "libelle": "Insee"
    },
    "NOM": {
        "libelle": "Nom"
    },
    "CARTE": {
        "libelle": "Carte"
    },
    "CODE_ORIGINE": {
        "libelle": "Code origine"
    },
    "RETENUE": {
        "libelle": "Retenue"
    },
    "SENS": {
        "libelle": "Sens"
    },
    "MC": {
        "libelle": "MC"
    },
    "NBU": {
        "libelle": "NBU",
        "type": "float"
    },
    "MONTANT": {
        "libelle": "Montant",
        "type": "float"
    },
    "LIBELLE": {
        "libelle": "Libellé"
    }
}',
    ],
    [
        'CODE'           => 'winpaie-indemnites',
        'LIBELLE'        => 'Extraction Winpaie indemnité de fin de contrat',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_export_paiement_indemnites',
        'CSV_PARAMS'     => '{
    "ANNEE_ID": {
        "visible": false
    },
    "PRIME_ID": {
        "visible": false
    },
    "TYPE_INTERVENANT": {
        "visible": false
    },
    "STRUCTURE_ID": {
        "visible": false
    },
    "STRUCTURE_IDS": {
        "visible": false
    },
    "PERIODE_ID": {
        "visible": false
    },
    "PERIODE_CODE": {
        "visible": false
    },
    "INTERVENANT_ID": {
        "visible": false
    },
    "INSEE": {
        "libelle": "Insee"
    },
    "NOM": {
        "libelle": "Nom"
    },
    "CARTE": {
        "libelle": "Carte"
    },
    "CODE_ORIGINE": {
        "libelle": "Code origine"
    },
    "RETENUE": {
        "libelle": "Retenue"
    },
    "SENS": {
        "libelle": "Sens"
    },
    "MC": {
        "libelle": "MC"
    },
    "NBU": {
        "libelle": "NBU",
        "type": "float"
    },
    "MONTANT": {
        "libelle": "Montant",
        "type": "float"
    },
    "LIBELLE": {
        "libelle": "Libellé"
    }
}',
    ],
    [
        'CODE'           => 'siham-indemnites',
        'LIBELLE'        => 'Extraction Siham indemnités de fin de contrat',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_export_paiement_indemnites_siham',
        'CSV_PARAMS'     => '{
     "ANNEE_ID": {
        "visible": false
    },
     "TYPE_INTERVENANT": {
        "visible": false
    },
     "PERIODE_CODE": {
        "visible": false
    },
    "PRIME_ID": {
        "visible": false
    },
    "STRUCTURE_IDS": {
        "visible": false
    },
    "TYPE_INTERVENANT_ID": {
        "visible": false
    },
    "STRUCTURE_ID": {
        "visible": false
    },
    "PERIODE_ID": {
        "visible": false
    },
    "INTERVENANT_ID": {
        "visible": false
    },
    "TYPE": {
        "libelle": "type"
    },
    "MATRICULE": {
        "libelle": "Matricule"
    },
     "CODE_INDEMNITE_RETENU": {
        "libelle": "Code indemnité/retenue"
    },
     "DU_MOIS": {
        "libelle": "Du mois (AAAA-MM)"
    },
     "ANNEE_DE_PAYE": {
        "libelle": "Année de paye (AA)"
    },
     "MOIS_DE_PAYE": {
        "libelle": "Mois de paye (MM)"
    },
    "NUMERO_DE_REMISE": {
        "libelle": "Numéro de remise"
    },
    "TG_SPECIFIQUE": {
        "libelle": "TG spécifique"
    },
    "DOSSIER_DE_PAYE": {
        "libelle": "Dossier de paye"
    },
    "DATE_PECUNIAIRE": {
        "libelle": "Date pécuniaire"
    },
    "NOMBRE_D_UNITES": {
        "libelle": "Nombre d’unités",
        "type": "float"
    },
    "MONTANT": {
        "libelle": "Montant",
        "type": "float"
    },
    "LIBELLE": {
        "libelle": "Libellé"
    },
    "MODE_DE_CALCUL": {
        "libelle": "Mode de calcul"
    },
    "CODE_ORIGINE": {
        "libelle": "Code origine"
    }
}',
    ],
    [
        'CODE'           => 'export-agrement',
        'LIBELLE'        => 'Export Agrément',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_agrement_export_csv',
        'CSV_PARAMS'     => '{
    "ANNEE_ID"            : { "visible": false },
    "INTERVENANT_ID"            : { "visible": false },
    "INTERVENANT_STRUCTURE_ID"            : { "visible": false },
    "STRUCTURE_ID"            : { "visible": false },
    "STRUCTURE_IDS"         : { "visible": false },
    "AGREE"            : { "visible": false },
    "ANNEE": { "libelle": "Année"},
    "INTERVENANT_CODE": { "libelle": "Code intervenant"},
    "STRUCTURE_LIBELLE": {"libelle": "Structure d\'enseignement"},
    "INTERVENANT_STRUCTURE_LIBELLE": {"libelle": "Structure d\'affectation"},
    "INTERVENANT_NOM_USUEL": {"libelle": "Nom usuel"},
    "INTERVENANT_NOM_PATRONYMIQUE": {"libelle": "Nom patronymique"},
    "INTERVENANT_PRENOM": {"libelle": "Prénom"},
    "INTERVENANT_STATUT_LIBELLE": {"libelle": "Statut"},
    "DISCIPLINE": {"libelle": "Discipline"},
    "HETD_FI": {"libelle": "HETD (FI)", "type": "float"},
    "HETD_FA": {"libelle": "HETD (FA)","type": "float"},
    "HETD_FC": {"libelle": "HETD (FC)","type": "float"},
    "HETD_TOTAL": {"libelle": "HETD (Total)","type": "float"},
    "TYPE_AGREMENT": {"libelle": "Type d\'agrément"},
    "AGREE_TXT": {"libelle": "Agréé"},
    "DATE_DECISION": {"libelle": "Date de décision"},
    "DATE_EXPIRATION": {"libelle": "Date d\'expiration"},
    "MODIFICATEUR": {"libelle": "Modificateur"},
    "DATE_MODIFICATION": {"libelle": "Date de modification"}
}',
    ],
    [
        'CODE'           => 'etat_paiement',
        'LIBELLE'        => 'État de paiement',
        'PDF_TRAITEMENT' => '/data/Etats de sortie/etat_paiement.php',
        'AUTO_BREAK'     => true,
        'REQUETE'        => 'SELECT * FROM v_etat_paiement',
        'CSV_PARAMS'     => '{
    "ANNEE_ID"                  : { "visible": false },
    "TYPE_INTERVENANT_ID"       : { "visible": false },
    "STATUT_ID"                 : { "visible": false },
    "STRUCTURE_ID"              : { "visible": false },
    "STRUCTURE_IDS"             : { "visible": false },
    "PERIODE_ID"                : { "visible": false },
    "INTERVENANT_ID"            : { "visible": false },
    "CENTRE_COUT_ID"            : { "visible": false },
    "DOMAINE_FONCTIONNEL_ID"    : { "visible": false },

    "ANNEE"                     : { "libelle": "Année" },
    "ETAT"                      : { "libelle": "État" },
    "COMPOSANTE"                : { "libelle": "Composante" },
    "DATE_MISE_EN_PAIEMENT"     : { "type": "date", "libelle": "Date de mise en paiement" },
    "PERIODE"                   : { "libelle": "Période" },
    "STATUT"                    : { "libelle": "Statut" },
    "INTERVENANT_CODE"          : { "libelle": "N° intervenant" },
    "INTERVENANT_NOM"           : { "libelle": "Intervenant" },
    "INTERVENANT_NUMERO_INSEE"  : { "libelle": "N° INSEE" },
    "CENTRE_COUT_CODE"          : { "libelle": "Centre de coûts ou EOTP (code)" },
    "CENTRE_COUT_LIBELLE"       : { "libelle": "Centre de coûts ou EOTP (libellé)" },
    "DOMAINE_FONCTIONNEL_CODE"  : { "libelle": "Domaine fonctionnel (code)" },
    "DOMAINE_FONCTIONNEL_LIBELLE" : { "libelle": "Domaine fonctionnel (libelle)" },
    "HETD"                      : { "type": "float", "libelle": "HETD" },
    "HETD_POURC"                : { "type": "float", "libelle": "HETD (%)" },
    "HETD_MONTANT"              : { "type": "float", "libelle": "HETD (€)" },
    "REM_FC_D714"               : { "type": "float", "libelle": "Rém. FC D714.60" },
    "EXERCICE_AA"               : { "type": "float", "libelle": "EXERCICE AA" },
    "EXERCICE_AA_MONTANT"       : { "type": "float", "libelle": "EXERCICE AA (€)" },
    "EXERCICE_AC"               : { "type": "float", "libelle": "EXERCICE AC" },
    "EXERCICE_AC_MONTANT"       : { "type": "float", "libelle": "EXERCICE AC (€)" }
}',
    ],
    [
        'CODE'           => 'export_services',
        'LIBELLE'        => 'Export des services',
        'PDF_TRAITEMENT' => '/data/Etats de sortie/export_services.php',
        'CSV_TRAITEMENT' => '/data/Etats de sortie/export_services_csv.php',
        'AUTO_BREAK'     => true,
        'REQUETE'        => 'SELECT 
    *
FROM 
    v_export_service
ORDER BY 
    intervenant_nom, 
    service_structure_ens_libelle, 
    etape_libelle, etablissement_libelle,
    element_libelle, fonction_referentiel_libelle',
    ],
    [
        'CODE'           => 'export-missions',
        'LIBELLE'        => 'Export CSV des missions',
        'PDF_TRAITEMENT' => null,
        'CSV_TRAITEMENT' => '/data/Etats de sortie/export_mission_csv.php',
        'REQUETE'        => 'select * FROM v_export_mission',
    ],
    [
        'CODE'           => 'imputation-budgetaire',
        'LIBELLE'        => 'Export des imputations budgétaires',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_imputation_budgetaire_siham',
        'CSV_PARAMS'     => '{
"ANNEE_ID"            : { "visible": false },
    "PERIODE_ID"            : { "visible": false },
    "INTERVENANT_ID"            : { "visible": false },
    "TYPE_INTERVENANT_ID"            : { "visible": false },
    "CENTRE_COUT_ID"            : { "visible": false },
    "DOMAINE_FONCTIONNEL_ID"            : { "visible": false },
    "ETAT"            : { "visible": false },
    "COMPOSANTE"            : { "visible": false },
    "DATE_MISE_EN_PAIEMENT"            : { "visible": false },
    "PERIODE"            : { "visible": false },
    "DOMAINE_FONCTIONNEL_CODE"            : { "visible": false },
    "HETD_POURC"            : { "visible": false },
    "HETD" : { "visible": false },
    "HETD_MONTANT"            : { "visible": false },
    "REM_FC_D714"            : { "visible": false },
    "NOMBRES_HEURES"    : { "visible": false },
                
    
    "TYPE"                     : { "libelle": "Type" },
    "UO"                      : { "libelle": "UO" },
    "MATRICULE"                      : { "libelle": "Matricule" },
    "DATE_DEBUT"                      : {"type": "date", "libelle": "Date de début" },
    "DATE_FIN"                      : { "type": "date","libelle": "Date de fin" },
    "CODE_INDEMNITE"                      : { "libelle": "Code indemnité" },
    "OPERATION"                      : { "libelle": "Opération" },
    "CENTRE_COUT"                      : { "libelle": "Centre de coût" },
    "DESTINATION"                      : { "libelle": "Destination" },
    "FONDS"                      : { "libelle": "Fonds" },
    "POSTE_RESERVATION_CREDIT"                      : { "libelle": "Poste de réservation de crédit" },
    "POURCENTAGE"                      : {"type": "float", "libelle": "Pourcentage" },
    "NOMBRES_HEURES"                      : { "libelle": "Nombre d\'heures" },
    "FLMODI"                      : { "libelle": "FLMODI" },
    "NUMORD"                      : { "libelle": "NUMORD" },
    "NUMGRP"                      : { "libelle": "NUMGRP" }
}',
    ],
    [
        'CODE'           => 'synthese-privilege',
        'LIBELLE'        => 'Synthèse des privilèges par rôle',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM v_synthese_privilege',
        'CSV_PARAMS'     => '',
    ],
    [
        'CODE'           => 'export-offre-formation',
        'LIBELLE'        => 'Export offre de formation',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE' => 'SELECT * FROM v_export_formation',
        'CSV_PARAMS'     => '{
            "ANNEE_ID"                  : { "visible": false },
            "STRUCTURE_ID"              : { "visible": false },
            "STRUCTURE_IDS"             : { "visible": false },
            "ETAPE_ID"                  : { "visible": false },
            
            "STRUCTURE"                 : { "libelle": "Structure"},
            "CODE_FORMATION"            : { "libelle": "Code formation"},
            "LIBELLE_FORMATION"         : { "libelle": "Libellé formation"},
            "NIVEAU"                    : { "libelle": "Niveau"},
            "CODE_ENSEIGNEMENT"         : { "libelle": "Code enseignement"},
            "LIBELLE_ENSEIGNEMENT"      : { "libelle": "Libellé enseignement"},
            "CODE_DISCIPLINE"           : { "libelle": "Code discipline"},
            "LIBELLE_DISCIPLINE"        : { "libelle": "Libellé discipline"},
            "PERIODE"                   : { "libelle": "Période"},
            "FOAD"                      : { "libelle": "FOAD"},
            "FI"                        : { "libelle": "Taux FI / effectifs année préc."},
            "FA"                        : { "libelle": "Taux FA / effectifs année préc."},
            "FC"                        : { "libelle": "Taux FC / effectifs année préc."},
            "EFFECTIF_FI"               : { "libelle": "Effectifs FI actuels"},
            "EFFECTIF_FA"               : { "libelle": "Effectifs FA actuels"},
            "EFFECTIF_FC"               : { "libelle": "Effectifs FC actuels"},
            "NB_GROUPE_CM"              : { "libelle": "Nbr groupes CM"},
            "HEURES_CM"                 : { "libelle": "Nbr heures CM"},
            "NB_GROUPE_TD"              : { "libelle": "Nbr groupes TD"},
            "HEURES_TD"                 : { "libelle": "Nbr heures TD"},
            "NB_GROUPE_TP"              : { "libelle": "Nbr groupes TP"},
            "HEURES_TP"                 : { "libelle": "Nbr heures TP"}
        }',
    ],
];
