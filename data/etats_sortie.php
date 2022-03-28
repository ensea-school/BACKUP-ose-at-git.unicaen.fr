<?php

return [
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
        'CODE'           => 'etat_paiement',
        'LIBELLE'        => 'État de paiement',
        'PDF_TRAITEMENT' => '/data/Etats de sortie/etat_paiement.php',
        'AUTO_BREAK'     => true,
        'REQUETE'        => 'SELECT * FROM v_etat_paiement',
        'CSV_PARAMS'     => '{
    "ANNEE_ID"                  : { "visible": false },
    "TYPE_INTERVENANT_ID"       : { "visible": false },
    "STRUCTURE_ID"              : { "visible": false },
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
];
