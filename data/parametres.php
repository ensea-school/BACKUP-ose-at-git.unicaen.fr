<?php

return [
    /* Années */
    "annee"                                      => [
        "DESCRIPTION" => "Année universitaire en cours pour la saisie des services",
    ],
    "annee_import"                               => [
        "DESCRIPTION" => "Année courante pour l'import",
    ],
    "annee_minimale_import_odf"                  => [
        "DESCRIPTION" => "Année minimale pour l'import de l'offre de formation (Paramètre éventuellement exploitable pour les filtres d'import)",
    ],


    /* IDS */
    "etablissement"                              => [
        "VALEUR"      => "0141408E",
        "DESCRIPTION" => "Identifiant de l'établissement courant",
        "QUERY"       => 'SELECT id valeur FROM etablissement WHERE source_code = :valeur AND histo_destruction IS NULL',
    ],
    "structure_univ"                             => [
        "DESCRIPTION" => "Composante représentant l'université (utile éventuellement pour la formule de calcul)",
    ],
    "oseuser"                                    => [
        "DESCRIPTION" => "Utilisateur OSE",
    ],
    "formule"                                    => [
        "VALEUR"      => "FORMULE_UNICAEN",
        "DESCRIPTION" => "Formule de calcul",
        "QUERY"       => 'SELECT id valeur FROM formule WHERE package_name = :valeur',
    ],
    "domaine_fonctionnel_ens_ext"                => [
        "VALEUR"      => "D102",
        "DESCRIPTION" => "Domaine fonctionnel à privilégier pour les enseignements pris à l'extérieur",
        "QUERY"       => 'SELECT id valeur FROM domaine_fonctionnel WHERE source_code = :valeur AND histo_destruction IS NULL',
    ],
    "scenario_charges_services"                  => [
        "VALEUR"      => "Initial",
        "DESCRIPTION" => "Scénario utilisé pour confronter les charges d'enseignement aux services des intervenants",
        "QUERY"       => 'SELECT id valeur FROM scenario WHERE libelle = :valeur AND histo_destruction IS NULL',
    ],


    /* Etats de sortie */
    "es_extraction_paie"                         => [
        "VALEUR"      => "winpaie",
        "DESCRIPTION" => "État de sortie pour l'extraction de paie",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    "es_services_pdf"                            => [
        "VALEUR"      => "export_services",
        "DESCRIPTION" => "État de sortie pour l'édition PDF des services",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    "es_services_csv"                            => [
        "VALEUR"      => "export_services",
        "DESCRIPTION" => "État de sortie pour l'édition CSV des services",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    "es_etat_paiement"                           => [
        "VALEUR"      => "etat_paiement",
        "DESCRIPTION" => "État de sortie pour les états de paiement",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],


    /* Semestriel / calendaire */
    "modalite_services_prev_ens"                 => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (prévisionnel, enseignements)",
    ],
    "modalite_services_prev_ref"                 => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (prévisionnel, référentiel)",
    ],
    "modalite_services_real_ens"                 => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (réalisé, enseignements)",
    ],
    "modalite_services_real_ref"                 => [
        "VALEUR"      => "semestriel",
        "DESCRIPTION" => "Modalité de gestion des services (réalisé, référentiel)",
    ],

    /* Divers */
    "report_service"                             => [
        "VALEUR"      => "PREVU",
        "DESCRIPTION" => "Report du service de l'année précédente",
    ],
    "constatation_realise"                       => [
        "VALEUR"      => "PREVU",
        "DESCRIPTION" => "Constatation du service fait",
    ],
    "centres_couts_paye"                         => [
        "VALEUR"      => "enseignement",
        "DESCRIPTION" => "Centres de coûts utilisés pour la paye",
    ],
    "regle_paiement_annee_civile"                => [
        "VALEUR"      => "4-6sur10",
        "DESCRIPTION" => "Règle de répartition années civiles antérieure / en cours pour les paiements",
    ],
    "regle_repartition_annee_civile"             => [
        "VALEUR"      => "prorata",
        "DESCRIPTION" => "Ventilation des heures AA/AC",
    ],
    "pourc_s1_pour_annee_civile"                 => [
        "VALEUR"      => "0.67",
        "DESCRIPTION" => "Taux de répartition en année civile pour les heures du premire semestre",
    ],

    /* Documentations */
    "doc-intervenant-vacataires"                 => [
        "DESCRIPTION" => "URL de la documentation OSE pour les vacataires",
    ],
    "doc-intervenant-permanents"                 => [
        "DESCRIPTION" => "URL de la documentation OSE pour les permanents",
    ],


    /* Disciplines */
    "discipline_codes_corresp_1_libelle"         => [
        "VALEUR"      => "Section(s) CNU Apogée",
        "DESCRIPTION" => "Libellé de la liste 1 des correspondances de codes des disciplines",
    ],
    "discipline_codes_corresp_2_libelle"         => [
        "VALEUR"      => "Section(s) CNU Harpège",
        "DESCRIPTION" => "Libellé de la liste 2 des correspondances de codes des disciplines",
    ],
    "discipline_codes_corresp_3_libelle"         => [
        "VALEUR"      => "Spécialité Harpège",
        "DESCRIPTION" => "Libellé de la liste 3 des correspondances de codes des disciplines",
    ],
    "discipline_codes_corresp_4_libelle"         => [
        "VALEUR"      => "Discipline du 2nd degré",
        "DESCRIPTION" => "Libellé de la liste 4 des correspondances de codes des disciplines",
    ],


    /* Statuts */
    "statut_intervenant_codes_corresp_1_libelle" => [
        "VALEUR"      => "Est ATV (oui ou non)",
        "DESCRIPTION" => "Libellé de la liste 2 des correspondances de codes des statuts (en majuscules séparés par des virgules)",
    ],
    "statut_intervenant_codes_corresp_2_libelle" => [
        "VALEUR"      => "Code Siham",
        "DESCRIPTION" => "Témoin précisant si le statut correspond à des intervenants ATV (saisir oui ou non)",
    ],
    "statut_intervenant_codes_corresp_3_libelle" => [
        "VALEUR"      => "Est VA (oui ou non)",
        "DESCRIPTION" => "Libellé de la liste 3 des correspondances de codes des statuts (en majuscules séparés par des virgules)",
    ],
    "statut_intervenant_codes_corresp_4_libelle" => [
        "VALEUR"      => null,
        "DESCRIPTION" => "Libellé de la liste 4 des correspondances de codes des statuts (en majuscules séparés par des virgules)",
    ],


    /* Contrat */
    "contrat_regle_franchissement"               => [
        "VALEUR"      => "validation",
        "DESCRIPTION" => "Règle de franchissement du contrat (comment considérer que l'étape \"Contrat\" est franchie dans le workflow)",
    ],
    "contrat_modele_mail"                        => [
        "VALEUR"      => "Bonjour :intervenant

Veuillez trouver en pièce jointe votre contrat à jour.

Cordialement,
:utilisateur",
        "DESCRIPTION" => "Modèle de mail pour l'envoi du contrat",
    ],
    "contrat_modele_mail_objet"                  => [
        "VALEUR"      => "Contrat :intervenant",
        "DESCRIPTION" => "Modèle de mail pour l'envoi du contrat",
    ],
    "contrat_mail_expediteur"                    => [
        "VALEUR"      => "",
        "DESCRIPTION" => "Email souhaité pour l'expéditeur du contrat",
    ],

    /* Export RH*/
    "export_rh_franchissement"                   => [
        "VALEUR"      => "",
        "DESCRIPTION" => "Etape de la feuille de route à franchir pour autoriser un export vers le SIRH",
    ],


    /* Messages informatifs */
    "page_contact"                               => [
        "VALEUR"      => "<h3>Intervenants</h3>
    Contactez votre composante ou bien écrivez à :
        <ul>
<li><a href=\"mailto:assistance-ose@unicaen . fr\" title=\"Cliquez pour rédiger un mail à destination de la liste d'échanges dédiée à l'assistance\">assistance-ose@unicaen.fr</a></li>
</ul>


<h3>Gestionnaires</h3>
    Postez un message sur la liste d'échanges des gestionnaires :
        <ul>
<li><a href=\"mailto:ose@liste.unicaen.fr\" title=\"Cliquez pour rédiger un mail à destination de la liste d'échanges des gestionnaires\">ose@liste.unicaen.fr</a></li>
</ul>",
        "DESCRIPTION" => "Contenu de la page \"Contact\"",
    ],

    "page_accueil" => [
        "VALEUR"      => "Bienvenue dans l'application de saisie des enseignements de l'université de Caen Normandie.",
        "DESCRIPTION" => "Message de la page d'accueil une fois connecté",
    ],

    "connexion_non_autorise" => [
        "VALEUR"      => "Votre statut ne vous permet pas de vous connecter à OSE.",
        "DESCRIPTION" => "Message informatif si l'intervenant n'est pas autorisé à se connecter",
    ],

    "connexion_sans_role_ni_statut" => [
        "VALEUR"      => "Vous n'êtes pas autorisé(e) à vous connecter à OSE avec ce compte. Nous vous prions de vous rapprocher de votre composante pour en obtenir un valide.",
        "DESCRIPTION" => "Message informatif si l'utilisateur n'est pas intervenant et n'a aucune affectation",
    ],

    /* Indicateur */
    "indicateur_email_expediteur"   => [
        "DESCRIPTION" => "Adresse email d'expéditeur des mails via les indicateur, si vide alors l'email de l'utilisateur sera utilisé",
    ],

    /* Contrat */
    "avenant"                       => [
        "VALEUR"      => "avenant_autorise",
        "DESCRIPTION" => "Permettre la création d'avenants au contrat",
    ],

    "contrat_direct" => [
        "VALEUR"      => "desactive",
        "DESCRIPTION" => "Permettre la création d'un contrat sans passé par le projet",
    ],

    "contrat_date" => [
        "VALEUR"      => "desactive",
        "DESCRIPTION" => "Permettre de saisir une date de retour signé pour un contrat sans ajouter de fichier",
    ],
];