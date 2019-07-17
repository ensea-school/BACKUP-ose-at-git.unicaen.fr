<?php

return [
    [
        'CODE'           => 'winpaie',
        'LIBELLE'        => 'Extraction Winpaie',
        'PDF_TRAITEMENT' => null,
        'AUTO_BREAK'     => false,
        'REQUETE'        => 'SELECT * FROM V_EXPORT_PAIEMENT_WINPAIE',
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
        'REQUETE'        => 'SELECT * FROM V_ETAT_PAIEMENT',
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
        'AUTO_BREAK'     => true,
        'REQUETE'        => 'SELECT 
    *
FROM 
    V_EXPORT_SERVICE
ORDER BY 
    INTERVENANT_NOM, 
    SERVICE_STRUCTURE_ENS_LIBELLE, 
    ETAPE_LIBELLE, ETABLISSEMENT_LIBELLE,
    ELEMENT_LIBELLE, FONCTION_REFERENTIEL_LIBELLE',
    ],
];
