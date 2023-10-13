<?php

return [
    "CANDIDATURE_SAISIE"             => [
        "LIBELLE_INTERVENANT" => "Je postule à une ou plusieurs offres d'emploi",
        "LIBELLE_AUTRES"      => "J'accède aux candidatures",
        "ROUTE"               => "intervenant/candidature",
        "DESC_NON_FRANCHIE"   => "Aucune candidature n'a été faite",
        "OBLIGATOIRE"         => true,
    ],
    "DONNEES_PERSO_SAISIE"           => [
        "LIBELLE_INTERVENANT" => "Je saisis mes données personnelles",
        "LIBELLE_AUTRES"      => "J'accède aux données personnelles",
        "ROUTE"               => "intervenant/dossier",
        "DESC_NON_FRANCHIE"   => "Les données personnelles n'ont pas été saisies",
        "OBLIGATOIRE"         => true,
    ],
    "SERVICE_SAISIE"                 => [
        "LIBELLE_INTERVENANT" => "Je saisis mes enseignements prévisionnels",
        "LIBELLE_AUTRES"      => "J'accède aux enseignements prévisionnels",
        "ROUTE"               => "intervenant/services-prevus",
        "DESC_NON_FRANCHIE"   => "Aucun enseignement prévisionnel n'a été saisi",
        "OBLIGATOIRE"         => true,
    ],
    "PJ_SAISIE"                      => [
        "LIBELLE_INTERVENANT" => "Je fournis les pièces justificatives",
        "LIBELLE_AUTRES"      => "J'accède aux pièces justificatives",
        "ROUTE"               => "piece-jointe/intervenant",
        "DESC_NON_FRANCHIE"   => "Les pièces justificatives n'ont pas été fournies",
        "OBLIGATOIRE"         => true,
    ],
    "PJ_VALIDATION"                  => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation des pièces justificatives",
        "LIBELLE_AUTRES"      => "Je visualise la validation des pièces justificatives",
        "ROUTE"               => "piece-jointe/intervenant",
        "DESC_NON_FRANCHIE"   => "Les pièces justificatives n'ont pas été validées",
        "OBLIGATOIRE"         => true,
    ],
    "DONNEES_PERSO_VALIDATION"       => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mes données personnelles",
        "LIBELLE_AUTRES"      => "Je visualise la validation des données personnelles",
        "ROUTE"               => "intervenant/dossier",
        "DESC_NON_FRANCHIE"   => "Les données personnelles n'ont pas été validées",
        "OBLIGATOIRE"         => true,
    ],
    "CANDIDATURE_VALIDATION"         => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mes candidatures",
        "LIBELLE_AUTRES"      => "J'accède à la validation des candidatures",
        "ROUTE"               => "intervenant/candidature",
        "DESC_NON_FRANCHIE"   => "Certaines candidatures attendent une réponse",
        "OBLIGATOIRE"         => true,
    ],
    "MISSION_SAISIE"                 => [
        "LIBELLE_INTERVENANT" => "Je visualise mes missions",
        "LIBELLE_AUTRES"      => "J'accède aux missions",
        "ROUTE"               => "intervenant/missions",
        "DESC_NON_FRANCHIE"   => "Aucune mission attibuée",
        "OBLIGATOIRE"         => true,
    ],
    "MISSION_VALIDATION"             => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mes missions",
        "LIBELLE_AUTRES"      => "Je valide les missions saisies",
        "ROUTE"               => "intervenant/missions",
        "DESC_NON_FRANCHIE"   => "Certaines missions n'ont pas été validées",
        "OBLIGATOIRE"         => true,
    ],
    "SERVICE_VALIDATION"             => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mes services prévisionnels",
        "LIBELLE_AUTRES"      => "Je visualise la validation des enseignements prévisionnels",
        "ROUTE"               => "intervenant/validation/enseignement/prevu",
        "DESC_NON_FRANCHIE"   => "Les enseignements prévisionnels n'ont pas été validés",
        "OBLIGATOIRE"         => true,
    ],
    "REFERENTIEL_VALIDATION"         => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mon référentiel prévisionnel",
        "LIBELLE_AUTRES"      => "Je visualise la validation du référentiel prévisionnel",
        "ROUTE"               => "intervenant/validation/referentiel/prevu",
        "DESC_NON_FRANCHIE"   => "Le référentiel prévisionnel n'a pas été validé",
        "OBLIGATOIRE"         => false,
    ],
    "CONSEIL_RESTREINT"              => [
        "LIBELLE_INTERVENANT" => "Je visualise l'agrément 'Conseil restreint'",
        "LIBELLE_AUTRES"      => "Je visualise l'agrément 'Conseil restreint'",
        "ROUTE"               => "intervenant/agrement/conseil-restreint",
        "DESC_NON_FRANCHIE"   => "L'agrément du Conseil Restreint n'a pas été saisi",
        "OBLIGATOIRE"         => true,
    ],
    "CONSEIL_ACADEMIQUE"             => [
        "LIBELLE_INTERVENANT" => "Je visualise l'agrément 'Conseil académique'",
        "LIBELLE_AUTRES"      => "Je visualise l'agrément 'Conseil académique'",
        "ROUTE"               => "intervenant/agrement/conseil-academique",
        "DESC_NON_FRANCHIE"   => "L'agrément du Conseil académique n'a pas été saisi",
        "OBLIGATOIRE"         => true,
    ],
    "CONTRAT"                        => [
        "LIBELLE_INTERVENANT" => "Je visualise mes contrat/avenants",
        "LIBELLE_AUTRES"      => "Je visualise le contrat et les avenants",
        "ROUTE"               => "intervenant/contrat",
        "DESC_NON_FRANCHIE"   => "Le contrat n'a pas été établi",
        "OBLIGATOIRE"         => true,
    ],
    "SERVICE_SAISIE_REALISE"         => [
        "LIBELLE_INTERVENANT" => "Je saisis mes enseignements réalisés",
        "LIBELLE_AUTRES"      => "J'accède aux enseignements réalisés",
        "ROUTE"               => "intervenant/services-realises",
        "DESC_NON_FRANCHIE"   => "Aucun enseignement réalisé n'a été saisi",
        "OBLIGATOIRE"         => true,
    ],
    "MISSION_SAISIE_REALISE"         => [
        "LIBELLE_INTERVENANT" => "Je renseigne mon suivi de mission",
        "LIBELLE_AUTRES"      => "J'accède au suivi de mission",
        "ROUTE"               => "intervenant/missions-suivi",
        "DESC_NON_FRANCHIE"   => "Aucune heure de mission réalisée n'a été renseignée",
        "OBLIGATOIRE"         => true,
    ],
    "CLOTURE_REALISE"                => [
        "LIBELLE_INTERVENANT" => "Je visualise la clôture de la saisie de mes services réalisés",
        "LIBELLE_AUTRES"      => "Je visualise la clôture de la saisie des services réalisés",
        "ROUTE"               => "intervenant/services-realises",
        "DESC_NON_FRANCHIE"   => "La clôture de saisie des services réalisés n'a pas été effectuée",
        "OBLIGATOIRE"         => true,
    ],
    "SERVICE_VALIDATION_REALISE"     => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mes services réalisés",
        "LIBELLE_AUTRES"      => "Je visualise la validation des enseignements réalisés",
        "ROUTE"               => "intervenant/validation/enseignement/realise",
        "DESC_NON_FRANCHIE"   => "Le service réalisé n'a été intégralement validé",
        "OBLIGATOIRE"         => true,
    ],
    "MISSION_VALIDATION_REALISE"     => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mon suivi de mission",
        "LIBELLE_AUTRES"      => "J'accède à la validation du suivi de mission",
        "ROUTE"               => "intervenant/missions-suivi",
        "DESC_NON_FRANCHIE"   => "Des heures de mission réalisées n'ont pas été validées",
        "OBLIGATOIRE"         => true,
    ],
    "MISSION_PRIME"                  => [
        "LIBELLE_INTERVENANT" => "Je visualise mes indemnités de fin de contrat",
        "LIBELLE_AUTRES"      => "Je visualise mes indemnités de fin de contrat",
        "ROUTE"               => "intervenant/prime-mission",
        "DESC_NON_FRANCHIE"   => "Aucune indemnité de fin de contrat à gérer",
        "OBLIGATOIRE"         => true,
    ],
    "REFERENTIEL_VALIDATION_REALISE" => [
        "LIBELLE_INTERVENANT" => "Je visualise la validation de mon référentiel réalisé",
        "LIBELLE_AUTRES"      => "Je visualise la validation du référentiel réalisé",
        "ROUTE"               => "intervenant/validation/referentiel/realise",
        "DESC_NON_FRANCHIE"   => "Le référentiel réalisé n'a pas été intégralement validé",
        "OBLIGATOIRE"         => false,
    ],
    "DEMANDE_MEP"                    => [
        "LIBELLE_INTERVENANT" => "Je visualise les demandes de mise en paiement me concernant",
        "LIBELLE_AUTRES"      => "J'accède aux demandes de mise en paiement",
        "ROUTE"               => "intervenant/mise-en-paiement/demande",
        "DESC_NON_FRANCHIE"   => "Aucune demande de mise en paiement n'a été faite",
        "OBLIGATOIRE"         => true,
        "DESC_SANS_OBJECTIF"  => "Le nombre d'heures de service réalisées ET validées n'est pas suffisant pour déclencher le paiement d'heures complémentaires.",
        "ROUTE_INTERVENANT"   => "intervenant/mise-en-paiement/visualisation",
    ],
    "SAISIE_MEP"                     => [
        "LIBELLE_INTERVENANT" => "Je visualise les mises en paiement me concernant",
        "LIBELLE_AUTRES"      => "J'accède aux mises en paiement",
        "ROUTE"               => "intervenant/mise-en-paiement/visualisation",
        "DESC_NON_FRANCHIE"   => "Aucune mise en paiement n'a été faite",
        "OBLIGATOIRE"         => true,
    ],
];