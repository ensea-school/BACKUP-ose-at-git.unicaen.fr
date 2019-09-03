<?php

return [
    /* Années */
    "annee"                              => [
        "DESCRIPTION" => "Année universitaire en cours pour la saisie des services",
    ],
    "annee_import"                       => [
        "DESCRIPTION" => "Année courante pour l'import",
    ],


    /* IDS */
    "etablissement"                      => [
        "VALEUR"      => "0141408E",
        "DESCRIPTION" => "Identifiant de l'établissement courant",
    ],
    "structure_univ"                     => [
        "DESCRIPTION" => "Composante représentant l'université (utile éventuellement pour la forpule de calcul)",
    ],
    "oseuser"                            => [
        "DESCRIPTION" => "Utilisateur OSE",
    ],
    "formule"                            => [
        "VALEUR"      => "FORMULE_UNICAEN",
        "DESCRIPTION" => "Formule de calcul",
    ],
    "domaine_fonctionnel_ens_ext"        => [
        "VALEUR"      => "D102",
        "DESCRIPTION" => "Domaine fonctionnel à privilégier pour les enseignements pris à l'extérieur",
    ],
    "scenario_charges_services"          => [
        "VALEUR"      => "Initial",
        "DESCRIPTION" => "Scénario utilisé pour confronter les charges d'enseignement aux services des intervenants",
    ],


    /* Etats de sortie */
    "es_winpaie"                         => [
        "VALEUR"      => "winpaie",
        "DESCRIPTION" => "État de sortie pour l'extraction Winpaie",
    ],
    "es_services_pdf"                    => [
        "VALEUR"      => "export_services",
        "DESCRIPTION" => "État de sortie pour l'édition PDF des services",
    ],
    "es_etat_paiement"                   => [
        "VALEUR"      => "etat_paiement",
        "DESCRIPTION" => "État de sortie pour les états de paiement",
    ],


    /* Semestriel / calendaire */
    "modalite_services_prev_ens"         => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (prévisionnel, enseignements)",
    ],
    "modalite_services_prev_ref"         => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (prévisionnel, référentiel)",
    ],
    "modalite_services_real_ens"         => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (réalisé, enseignements)",
    ],
    "modalite_services_real_ref"         => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (réalisé, référentiel)",
    ],


    /* Documentations */
    "doc-intervenant-vacataires"         => [
        "DESCRIPTION" => "URL de la documentation OSE pour les vacataires",
    ],
    "doc-intervenant-permanents"         => [
        "DESCRIPTION" => "URL de la documentation OSE pour les permanents",
    ],


    /* Disciplines */
    "discipline_codes_corresp_1_libelle" => [
        "VALEUR"      => "Section(s) CNU Apogée",
        "DESCRIPTION" => "Libellé de la liste 1 des correspondances de codes des disciplines",
    ],
    "discipline_codes_corresp_2_libelle" => [
        "VALEUR"      => "Section(s) CNU Harpège",
        "DESCRIPTION" => "Libellé de la liste 2 des correspondances de codes des disciplines",
    ],
    "discipline_codes_corresp_3_libelle" => [
        "VALEUR"      => "Spécialité Harpège",
        "DESCRIPTION" => "Libellé de la liste 3 des correspondances de codes des disciplines",
    ],
    "discipline_codes_corresp_4_libelle" => [
        "VALEUR"      => "Discipline du 2nd degré",
        "DESCRIPTION" => "Libellé de la liste 4 des correspondances de codes des disciplines",
    ],
];