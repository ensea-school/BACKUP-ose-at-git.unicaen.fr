<?php

use Administration\Entity\Db\Parametre;

return [
    /* Années */
    Parametre::ANNEE                                      => [
        "DESCRIPTION" => "Année universitaire en cours pour la saisie des services",
    ],
    Parametre::ANNEE_IMPORT                               => [
        "DESCRIPTION" => "Année courante pour l'import",
    ],
    Parametre::ANNEE_MINIMALE_IMPORT_ODF                  => [
        "DESCRIPTION" => "Année minimale pour l'import de l'offre de formation (Paramètre éventuellement exploitable pour les filtres d'import)",
    ],


    /* IDS */
    Parametre::ETABLISSEMENT                              => [
        "VALEUR"      => "0141408E",
        "DESCRIPTION" => "Identifiant de l'établissement courant",
        "QUERY"       => 'SELECT id valeur FROM etablissement WHERE source_code = :valeur AND histo_destruction IS NULL',
    ],
    Parametre::STRUCTURE_UNIV                             => [
        "DESCRIPTION" => "Composante représentant l'université (utile éventuellement pour la formule de calcul)",
    ],
    Parametre::OSEUSER                                    => [
        "DESCRIPTION" => "Utilisateur OSE",
    ],
    Parametre::FORMULE                                    => [
        "VALEUR"      => "FORMULE_UNICAEN",
        "DESCRIPTION" => "Formule de calcul",
        "QUERY"       => 'SELECT id valeur FROM formule WHERE code = :valeur',
    ],
    Parametre::DOMAINE_FONCTIONNEL_ENS_EXT                => [
        "VALEUR"      => "D102",
        "DESCRIPTION" => "Domaine fonctionnel à privilégier pour les enseignements pris à l'extérieur",
        "QUERY"       => 'SELECT id valeur FROM domaine_fonctionnel WHERE source_code = :valeur AND histo_destruction IS NULL',
    ],
    Parametre::SCENARIO_CHARGES_SERVICES                  => [
        "VALEUR"      => "Initial",
        "DESCRIPTION" => "Scénario utilisé pour confronter les charges d'enseignement aux services des intervenants",
        "QUERY"       => 'SELECT id valeur FROM scenario WHERE libelle = :valeur AND histo_destruction IS NULL',
    ],


    /* Etats de sortie */
    Parametre::ES_EXTRACTION_PAIE                         => [
        "VALEUR"      => "winpaie",
        "DESCRIPTION" => "État de sortie pour l'extraction de paie",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    Parametre::ES_EXTRACTION_INDEMNITES                   => [
        "VALEUR"      => "siham-indemnites",
        "DESCRIPTION" => "État de sortie pour l'extraction des indemnites de fin de mission",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    Parametre::ES_SERVICES_PDF                            => [
        "VALEUR"      => "export_services",
        "DESCRIPTION" => "État de sortie pour l'édition PDF des services",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    Parametre::ES_SERVICES_CSV                            => [
        "VALEUR"      => "export_services",
        "DESCRIPTION" => "État de sortie pour l'édition CSV des services",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    Parametre::ES_ETAT_PAIEMENT                           => [
        "VALEUR"      => "etat_paiement",
        "DESCRIPTION" => "État de sortie pour les états de paiement",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],
    Parametre::ES_EXPORT_FORMATION => [
        "VALEUR"      => "export-offre-formation",
        "DESCRIPTION" => "État de sortie pour l'export offre de formation",
        "QUERY"       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
    ],



    /* Divers */
    Parametre::REPORT_SERVICE                             => [
        "VALEUR"      => "PREVU",
        "DESCRIPTION" => "Report du service de l'année précédente",
    ],
    Parametre::CONSTATATION_REALISE                       => [
        "VALEUR"      => "PREVU",
        "DESCRIPTION" => "Constatation du service fait",
    ],


    /* Paiement */
    Parametre::CENTRES_COUTS_PAYE                         => [
        "VALEUR"      => "enseignement",
        "DESCRIPTION" => "Centres de coûts utilisés pour la paye",
    ],
    Parametre::REGLE_PAIEMENT_ANNEE_CIVILE                => [
        "VALEUR"      => "4-6sur10",
        "DESCRIPTION" => "Règle de répartition années civiles antérieure / en cours pour les paiements",
    ],
    Parametre::REGLE_REPARTITION_ANNEE_CIVILE             => [
        "VALEUR"      => "prorata",
        "DESCRIPTION" => "Ventilation des heures AA/AC",
    ],
    Parametre::POURC_AA_REFERENTIEL                       => [
        "VALEUR"      => '0.4',
        "DESCRIPTION" => "Pourcentage AA pour les heures de référentiel",
    ],
    Parametre::POURC_S1_POUR_ANNEE_CIVILE                 => [
        "VALEUR"      => "0.67",
        "DESCRIPTION" => "Taux de répartition en année civile pour les heures du premire semestre",
    ],
    Parametre::HORAIRE_NOCTURNE                           => [
        "VALEUR"      => "22:00",
        "DESCRIPTION" => "Horaire à partir duquel les heures faites sont considérées comme nocturnes",
    ],
    Parametre::TAUX_REMU                                  => [
        "VALEUR"      => "TLD",
        "DESCRIPTION" => "taux de rémuneration utilisé par défaut",
        "QUERY"       => 'SELECT id valeur FROM taux_remu WHERE code = :valeur AND histo_destruction IS NULL',

    ],
    Parametre::TAUX_CONGES_PAYES                          => [
        "VALEUR"      => 0.1,
        "DESCRIPTION" => "Taux de majoration des heures pour prise en compte des congés payés",
    ],
    Parametre::DISTINCTION_FI_FA_FC                       => [
        "VALEUR"      => 1,
        "DESCRIPTION" => "Distinction FI/FA/FC des heures à payer",
    ],


    /* Documentations */
    Parametre::DOC_INTERVENANT_VACATAIRES                 => [
        "DESCRIPTION" => "URL de la documentation OSE pour les vacataires",
    ],
    Parametre::DOC_INTERVENANT_PERMANENTS                 => [
        "DESCRIPTION" => "URL de la documentation OSE pour les permanents",
    ],
    Parametre::DOC_INTERVENANT_ETUDIANTS                  => [
        "DESCRIPTION" => "URL de la documentation OSE pour les étudiants",
    ],


    /* Disciplines */
    Parametre::DISCIPLINE_CODES_CORRESP_1_LIBELLE         => [
        "VALEUR"      => "Section(s) CNU Apogée",
        "DESCRIPTION" => "Libellé de la liste 1 des correspondances de codes des disciplines",
    ],
    Parametre::DISCIPLINE_CODES_CORRESP_2_LIBELLE         => [
        "VALEUR"      => "Section(s) CNU Harpège",
        "DESCRIPTION" => "Libellé de la liste 2 des correspondances de codes des disciplines",
    ],
    Parametre::DISCIPLINE_CODES_CORRESP_3_LIBELLE         => [
        "VALEUR"      => "Spécialité Harpège",
        "DESCRIPTION" => "Libellé de la liste 3 des correspondances de codes des disciplines",
    ],
    Parametre::DISCIPLINE_CODES_CORRESP_4_LIBELLE         => [
        "VALEUR"      => "Discipline du 2nd degré",
        "DESCRIPTION" => "Libellé de la liste 4 des correspondances de codes des disciplines",
    ],


    /* Statuts */
    Parametre::STATUT_INTERVENANT_CODES_CORRESP_1_LIBELLE => [
        "VALEUR"      => "Code Siham",
        "DESCRIPTION" => "Code équivalent au statut SIHAM",
    ],
    Parametre::STATUT_INTERVENANT_CODES_CORRESP_2_LIBELLE => [
        "VALEUR"      => "Est ATV (oui ou non)",
        "DESCRIPTION" => "Témoin précisant si le statut correspond à des intervenants ATV (saisir oui ou non)",
    ],
    Parametre::STATUT_INTERVENANT_CODES_CORRESP_3_LIBELLE => [
        "VALEUR"      => "Est VA (oui ou non)",
        "DESCRIPTION" => "Libellé de la liste 3 des correspondances de codes des statuts (en majuscules séparés par des virgules)",
    ],
    Parametre::STATUT_INTERVENANT_CODES_CORRESP_4_LIBELLE => [
        "VALEUR"      => null,
        "DESCRIPTION" => "Libellé de la liste 4 des correspondances de codes des statuts (en majuscules séparés par des virgules)",
    ],


    /* Contrat */
    Parametre::CONTRAT_REGLE_FRANCHISSEMENT               => [
        "VALEUR"      => "validation",
        "DESCRIPTION" => "Règle de franchissement du contrat (comment considérer que l'étape \"Contrat\" est franchie dans le workflow)",
    ],
    Parametre::CONTRAT_MODELE_MAIL                        => [
        "VALEUR"      => "Bonjour :intervenant

Veuillez trouver en pièce jointe votre contrat à jour.

Cordialement,
:utilisateur",
        "DESCRIPTION" => "Modèle de mail pour l'envoi du contrat",
    ],
    Parametre::CONTRAT_MODELE_MAIL_OBJET                  => [
        "VALEUR"      => "Contrat :intervenant",
        "DESCRIPTION" => "Modèle de mail pour l'envoi du contrat",
    ],
    Parametre::CONTRAT_MAIL_EXPEDITEUR                    => [
        "VALEUR"      => "",
        "DESCRIPTION" => "Email souhaité pour l'expéditeur du contrat",
    ],
    Parametre::AVENANT                                    => [
        "VALEUR"      => "avenant_autorise",
        "DESCRIPTION" => "Permettre la création d'avenants au contrat",
    ],

    Parametre::CONTRAT_DIRECT => [
        "VALEUR"      => "desactive",
        "DESCRIPTION" => "Permettre la création d'un contrat sans passé par le projet",
    ],

    Parametre::CONTRAT_MIS => [
        "VALEUR"      => "contrat_mis_mission",
        "DESCRIPTION" => "Paramètre sur quelles missions un contrat porte",
    ],

    Parametre::CONTRAT_ENS                               => [
        "VALEUR"      => "contrat_ens_composante",
        "DESCRIPTION" => "Paramètre sur quels enseignements un contrat porte",
    ],
    Parametre::CONTRAT_DATE                              => [
        "VALEUR"      => "desactive",
        "DESCRIPTION" => "Permettre de saisir une date de retour signé pour un contrat sans ajouter de fichier",
    ],


    /* Candidature mission */
    Parametre::CANDIDATURE_MODELE_ACCEPTATION_MAIL       => [
        "VALEUR"      => "Bonjour :intervenant

Vous avez récement postulé à une offre d'emploi étudiant. c'est acceptée

Cordialement,
:utilisateur",
        "DESCRIPTION" => "Modèle de mail acceptation d'une candidature",
    ],
    Parametre::CANDIDATURE_MODELE_ACCEPTATION_MAIL_OBJET => [
        "VALEUR"      => "Acceptation candidature :intervenant",
        "DESCRIPTION" => "Sujet pour le mail d'acceptation de candidature",
    ],
    Parametre::CANDIDATURE_MODELE_REFUS_MAIL             => [
        "VALEUR"      => "Bonjour :intervenant

Vous avez récement postulé à une offre d'emploi étudiant. Mais c'est refusé

Cordialement,
:utilisateur",
        "DESCRIPTION" => "Modèle de mail de refus d'une candidature",
    ],
    Parametre::CANDIDATURE_MODELE_REFUS_MAIL_OBJET       => [
        "VALEUR"      => "Refus candidature :intervenant",
        "DESCRIPTION" => "Sujet pour le mail de refus de candidature",
    ],
    Parametre::CANDIDATURE_MAIL_EXPEDITEUR               => [
        "VALEUR"      => "",
        "DESCRIPTION" => "Email souhaité pour l'expéditeur du mail candidature",
    ],


    /* Signature électronique */
    Parametre::SIGNATURE_ELECTRONIQUE_PARAPHEUR          => [
        "VALEUR"      => "",
        "DESCRIPTION" => "Choix du parpaheur électronique pour l'application",
    ],


    /* Messages informatifs */
    Parametre::PAGE_CONTACT                              => [
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

    Parametre::PAGE_ACCUEIL => [
        "VALEUR"      => "Bienvenue dans l'application de saisie des enseignements de l'université de Caen Normandie.",
        "DESCRIPTION" => "Message de la page d'accueil une fois connecté",
    ],

    Parametre::CONNEXION_NON_AUTORISE => [
        "VALEUR"      => "Votre statut ne vous permet pas de vous connecter à OSE.",
        "DESCRIPTION" => "Message informatif si l'intervenant n'est pas autorisé à se connecter",
    ],

    Parametre::CONNEXION_SANS_ROLE_NI_STATUT => [
        "VALEUR"      => "Vous n'êtes pas autorisé(e) à vous connecter à OSE avec ce compte. Nous vous prions de vous rapprocher de votre composante pour en obtenir un valide.",
        "DESCRIPTION" => "Message informatif si l'utilisateur n'est pas intervenant et n'a aucune affectation",
    ],


    /* Indicateur */
    Parametre::INDICATEUR_EMAIL_EXPEDITEUR   => [
        "DESCRIPTION" => "Adresse email d'expéditeur des mails via les indicateur, si vide alors l'email de l'utilisateur sera utilisé",
    ],

];