<?php

return [
    'odf' => [
        'libelle'    => 'Gestion de l\'offre de formation',
        'privileges' => [
            'visualisation'                      => 'Visualisation',
            'export-csv'                         => 'Export CSV',
            'element-visualisation'              => 'Enseignements - Visualisation',
            'element-edition'                    => 'Enseignements - Édition',
            'element-synchronisation'            => 'Enseignements - Synchronisation',
            'etape-visualisation'                => 'Formations - Visualisation',
            'etape-edition'                      => 'Formations - Édition',
            'type-formation-visualisation'       => 'Type formations - Visualisation',
            'type-formation-edition'             => 'Type formations - Édition',
            'centres-cout-edition'               => 'Centres de coûts - Édition',
            'modulateurs-edition'                => 'Modulateurs - Édition',
            'taux-mixite-edition'                => 'Taux de mixité - Édition',
            'element-vh-edition'                 => 'Enseignements - Volumes horaires - Édition',
            'element-vh-visualisation'           => 'Enseignements - Volumes horaires - Visualisation',
            'grands-types-diplome-visualisation' => 'Grands types de diplômes (visualisation)',
            'grands-types-diplome-edition'       => 'Grands types de diplômes (édition)',
            'types-diplome-visualisation'        => 'Types de diplômes (visualisation)',
            'types-diplome-edition'              => 'Types de diplômes (édition)',
            'reconduction-offre'                 => 'Prolongation de l\'offre l\'année suivante',
            'reconduction-centre-cout'           => 'Reconduction des centres de coûts de l\'offre de formation',
            'reconduction-modulateur'            => 'Reconduction des modulateurs de l\'offre de formation',
        ],
    ],

    'discipline' => [
        'libelle'    => 'Gestion des disciplines',
        'privileges' => [
            'visualisation' => 'Visualisation',
            'edition'       => 'Édition',
            'gestion'       => 'Gestion',
        ],
    ],

    'intervenant' => [
        'libelle'    => 'Intervenant',
        'privileges' => [
            'recherche'                => 'Recherche',
            'fiche'                    => 'Visualisation de la fiche',
            'adresse'                  => 'Visualisation de l\'adresse',
            'calcul-hetd'              => 'Calcul HETD',
            'creation'                 => 'Création',
            'ajout-statut'             => 'Ajout d\'un nouveau statut',
            'visualisation-historises' => 'Voir les intervenants historisés',
            'edition'                  => 'Edition',
            'edition-avancee'          => 'Edition avancée',
            'suppression'              => 'Suppression',
            'exporter'                 => 'Exporter vers SIRH',
            'statut-edition'           => 'Statuts (Édition)',
            'statut-visualisation'     => 'Statuts (Visualisation)',
            'autres-visualisation'     => 'Champs autres (Visualisation)',
            'autres-edition'           => 'Champs autres (Edition)',
            'lien-systeme-information' => 'Afficher le lien vers le système d\'information dans la fiche',
            'note-visualisation'       => 'Visualiser des notes',
            'note-ajout'               => 'Ajouter des notes',
            'note-administration'      => 'Administrer les notes',
            'note-email'               => 'Envoyer un email à l\'intervenant',
        ],
    ],

    'modif-service-du' => [
        'libelle'    => 'Modification de service dû',
        'privileges' => [
            'association'           => 'Association',
            'visualisation'         => 'Visualisation',
            'edition'               => 'Édition',
            'export-csv'            => 'Export CSV',
            'gestion-edition'       => 'Gestion (édition)',
            'gestion-visualisation' => 'Gestion (visualisation)',
        ],
    ],

    'dossier' => [
        'libelle'    => 'Données personnelles',
        'privileges' => [
            'visualisation'                => 'Visualisation',
            'edition'                      => 'Édition',
            'validation'                   => 'Validation',
            'suppression'                  => 'Suppression',
            'devalidation'                 => 'Dévalidation',
            'visualisation-complementaire' => 'Visualisation données complementaires',
            'edition-complementaire'       => 'Édition données complementaires',
            'validation-complementaire'    => 'Validation données complementaires',
            'devalidation-complementaire'  => 'Dévalidation données complementaires',
            'differences'                  => 'Différences avec Harpège',
            'purger-differences'           => 'Purger les différences',
            'identite-visualisation'       => 'Identité - Visualisation',
            'identite-edition'             => 'Identité - Édition',
            'adresse-visualisation'        => 'Adresse - Visualisation',
            'adresse-edition'              => 'Adresse - Édition',
            'contact-visualisation'        => 'Contact - Visualisation',
            'contact-edition'              => 'Contact - Édition',
            'insee-visualisation'          => 'N° Insée - Visualisation',
            'insee-edition'                => 'N° Insée - Édition',
            'banque-visualisation'         => 'Coord. Banque - Visualisation',
            'banque-edition'               => 'Coord. Banque - Édition',
            'employeur-visualisation'      => 'Employeur - Visualisation',
            'employeur-edition'            => 'Employeur - Édition',
            'champ-autre-1-visualisation'  => 'Champ autre 1 - Visualisation',
            'champ-autre-1-edition'        => 'Champ autre 1 - Édition',
            'champ-autre-2-visualisation'  => 'Champ autre 2 - Visualisation',
            'champ-autre-2-edition'        => 'Champ autre 2 - Édition',
            'champ-autre-3-visualisation'  => 'Champ autre 3 - Visualisation',
            'champ-autre-3-edition'        => 'Champ autre 3 - Édition',
            'champ-autre-4-visualisation'  => 'Champ autre 4 - Visualisation',
            'champ-autre-4-edition'        => 'Champ autre 4 - Édition',
            'champ-autre-5-visualisation'  => 'Champ autre 5 - Visualisation',
            'champ-autre-5-edition'        => 'Champ autre 5 - Édition',
        ],
    ],

    'piece-justificative' => [
        'libelle'    => 'Pièces justificatives',
        'privileges' => [
            'visualisation'         => 'Visualisation',
            'edition'               => 'Édition',
            'validation'            => 'Validation',
            'refus-piece'           => 'Refus - Pièce justificative',
            'devalidation'          => 'Dévalidation',
            'archivage'             => 'Archivage',
            'gestion-edition'       => 'Gestion des pièces justificatives (édition)',
            'gestion-visualisation' => 'Gestion des pièces justificatives (visualisation)',
            'telechargement'        => 'Téléchargement',
        ],
    ],

    'enseignement' => [
        'libelle'    => 'Enseignement',
        'privileges' => [
            'edition-masse'                          => 'Édition en masse',
            'prevu-visualisation'                    => 'Visualisation du prévisionnel',
            'prevu-edition'                          => 'Édition du prévisionnel',
            'prevu-validation'                       => 'Validation du prévisionnel',
            'prevu-autovalidation'                   => 'Validation automatique du prévisionnel',
            'realise-visualisation'                  => 'Visualisation du réalisé',
            'realise-edition'                        => 'Édition du réalisé',
            'realise-validation'                     => 'Validation du réalisé',
            'realise-autovalidation'                 => 'Validation automatique du réalisé',
            'devalidation'                           => 'Dévalidation',
            'exterieur'                              => 'Saisie de service dans une autre université',
            'export-pdf'                             => 'Export PDF',
            'export-csv'                             => 'Export CSV',
            'import-intervenant-previsionnel-agenda' => 'Import service prévisionnel depuis agenda',
            'import-intervenant-realise-agenda'      => 'Import service réalisé depuis agenda',
        ],
    ],

    'mission' => [
        'libelle'    => 'Missions',
        'privileges' => [
            'visualisation'              => 'Visualisation',
            'visualisation-realise'      => 'Visualisation des heures réalisées',
            'edition'                    => 'Edition',
            'validation'                 => 'Validation',
            'devalidation'               => 'Dévalidation',
            'autovalidation'             => 'Autovalidation',
            'edition-realise'            => 'Edition des heures réalisées',
            'validation-realise'         => 'Validation des heures réalisées',
            'devalidation-realise'       => 'Dévalidation des heures réalisées',
            'autovalidation-realise'     => 'Auto-validation des heures réalisées',
            'visualisation-type'         => 'Visualisation des types de mission',
            'edition-type'               => 'Edition des types de mission',
            'suppression-type'           => 'Suppression des types de mission',
            'offre-emploi-visualisation' => 'Visualisation des offres d\'emploi',
            'offre-emploi-suppression'   => 'Suppresion des offres d\'emploi',
            'offre-emploi-ajouter'       => 'Ajouter des offres d\'emploi',
            'offre-emploi-modifier'      => 'Modifier des offres d\'emploi',
            'offre-emploi-valider'       => 'Valider des offres d\'emploi',
            'offre-emploi-postuler'      => 'Postuler à une offre d\'emploi',
            'candidature-valider'        => 'Valider une candidature',
            'candidature-refuser'        => 'Refuser une candidature',
            'candidature-visualisation'  => 'Visualiser les candidatures d\'une offre',
            'prime-visualisation'        => 'Visualiser les primes de fin de mission',
            'prime-gestion'              => 'Gérer les primes de fin de mission',
            'edition-centre-cout-type'   => 'Édition des centres de coûts d\'un type de mission',
        ],
    ],

    'motif-non-paiement' => [
        'libelle'    => 'Motifs de non paiement (pour enseignements)',
        'privileges' => [
            'visualisation'                => 'Visualisation',
            'edition'                      => 'Édition',
            'administration-visualisation' => 'Administration - Visualisation',
            'administration-edition'       => 'Administration - Édition',
        ],
    ],
    'tag'                => [
        'libelle'    => 'Tag',
        'privileges' => [
            'visualisation'                => 'Visualisation',
            'edition'                      => 'Édition',
            'administration-visualisation' => 'Administration - Visualisation',
            'administration-edition'       => 'Administration - Édition',
        ],
    ],

    'referentiel' => [
        'libelle'    => 'Référentiel',
        'privileges' => [
            'prevu-visualisation'       => 'Visualisation du prévisionnel',
            'prevu-edition'             => 'Édition du prévisionnel',
            'prevu-validation'          => 'Validation du prévisionnel',
            'prevu-autovalidation'      => 'Validation automatique du prévisionnel',
            'realise-visualisation'     => 'Visualisation du réalisé',
            'realise-edition'           => 'Édition du réalisé',
            'realise-validation'        => 'Validation du réalisé',
            'realise-autovalidation'    => 'Validation automatique du réalisé',
            'admin-edition'             => 'Administration - Édition',
            'devalidation'              => 'Dévalidation',
            'admin-visualisation'       => 'Administration - Visualisation',
            'saisie-toutes-composantes' => 'Saisie sans contrainte de composante',
        ],
    ],

    'agrement' => [
        'libelle'    => 'Agréments',
        'privileges' => [
            'conseil-academique-visualisation' => 'Conseil académique - Visualisation',
            'conseil-academique-edition'       => 'Conseil académique - Édition',
            'conseil-restreint-visualisation'  => 'Conseil restreint - Visualisation',
            'conseil-restreint-edition'        => 'Conseil restreint - Édition',
            'conseil-academique-suppression'   => 'Conseil académique - Suppression',
            'conseil-restreint-suppression'    => 'Conseil restreint - Suppression',
            'export-csv'                       => 'Export CSV',
        ],
    ],

    'contrat' => [
        'libelle'    => 'Contrats de travail/Avenants',
        'privileges' => [
            'visualisation'                  => 'Visualisation',
            'creation'                       => 'Création d\'un projet',
            'suppression'                    => 'Suppression d\'un projet',
            'validation'                     => 'Validation',
            'devalidation'                   => 'Dévalidation',
            'depot-retour-signe'             => 'Dépôt de contrat signé',
            'saisie-date-retour-signe'       => 'Saisie de date retour',
            'projet-generation'              => 'Génération de projet de contrat',
            'contrat-generation'             => 'Génération de contrat',
            'envoi-email'                    => 'Envoyer le contrat par email',
            'envoyer-signature-electronique' => 'Envoyer un contrat pour signature électronique',
        ],
    ],

    'mise-en-paiement' => [
        'libelle'    => 'Mises en paiement',
        'privileges' => [
            'visualisation-gestion'     => 'Visualisation (Gestion)',
            'demande'                   => 'Demande',
            'export-csv'                => 'Export CSV',
            'export-pdf'                => 'Export PDF mise en paiement',
            'export-pdf-etat'           => 'Export PDF état paiement',
            'mise-en-paiement'          => 'Mise en paiement',
            'export-paie'               => 'Export vers le logiciel de paie',
            'edition'                   => 'Annulation de mises en paiement',
            'details'                   => 'Détail des calculs de mises en paiement',
            'visualisation-intervenant' => 'Visualisation (Intervenant)',
        ],
    ],

    'indicateur' => [
        'libelle'    => 'Indicateurs',
        'privileges' => [
            'visualisation'             => 'Visualisation',
            'abonnement'                => 'Abonnement',
            'abonnements-edition'       => 'Abonnements - Édition',
            'abonnements-visualisation' => 'Abonnements - Visualisation',
            'envoi-mail-intervenants'   => 'Mail aux intervenants',
        ],
    ],

    'droit' => [
        'libelle'    => 'Gestion des droits d\'accès',
        'privileges' => [
            'role-visualisation'        => 'Rôles - Visualisation',
            'role-edition'              => 'Rôles - Édition',
            'privilege-visualisation'   => 'Privilèges - Visualisation',
            'privilege-edition'         => 'Privilèges - Édition',
            'affectation-visualisation' => 'Affectations - Visualisation',
            'affectation-edition'       => 'Affectations - Édition',
        ],
    ],

    'import' => [
        'libelle'    => 'Import',
        'privileges' => [
            'ecarts'                => 'Écarts',
            'maj'                   => 'Mise à jour',
            'tbl'                   => 'Tableau de bord',
            'vues-procedures'       => 'Gestion des vues et procédures',
            'sources-edition'       => 'Sources (édition)',
            'sources-visualisation' => 'Sources (visualisation)',
            'tables-edition'        => 'Tables (édition)',
            'tables-visualisation'  => 'Tables (visualisation)',
        ],
    ],

    'type-intervention' => [
        'libelle'    => 'Type d\'intervention',
        'privileges' => [
            'visualisation' => 'Visualisation',
            'edition'       => 'Édition',
        ],
    ],

    'type-ressource' => [
        'libelle'    => 'Types de ressources',
        'privileges' => [
            'visualisation' => 'Visualisation',
            'edition'       => 'Édition',
        ],
    ],

    'modulateur' => [
        'libelle'    => 'Modulateurs',
        'privileges' => [
            'edition'       => 'Édition',
            'visualisation' => 'Visualisation',
        ],
    ],

    'budget' => [
        'libelle'    => 'Budget',
        'privileges' => [
            'visualisation'                    => 'Visualisation',
            'edition-engagement-composante'    => 'Dotation ressources propres',
            'export'                           => 'Export CSV',
            'edition-engagement-etablissement' => 'Dotation paye état',
            'type-dotation-edition'            => 'Types de dotation - Édition',
            'type-dotation-visualisation'      => 'Types de dotation - Visualisation',
            'cc-activite-visualisation'        => 'CC activité - Visualisation',
            'cc-activite-edition'              => 'CC activité - Édition',
            'types-ressources-visualisation'   => 'Types de ressources - Visualisation',
            'types-ressources-edition'         => 'Types de ressources - Édition',
        ],
    ],

    'pilotage' => [
        'libelle'    => 'Pilotage',
        'privileges' => [
            'ecarts-etats'  => 'Ecarts d\'heures entre états',
        ],
    ],

    'chargens' => [
        'libelle'    => 'Charges d\'enseignement',
        'privileges' => [
            'formation-assiduite-edition'       => 'Édition des formations (assiduité)',
            'formation-effectifs-edition'       => 'Édition des formations (effectifs)',
            'formation-seuils-edition'          => 'Édition des formations (seuils)',
            'formation-visualisation'           => 'Visualisation des formations',
            'scenario-composante-edition'       => 'Édition des scénarios (composantes)',
            'scenario-etablissement-edition'    => 'Édition des scénarios (établissement)',
            'scenario-visualisation'            => 'Visualisation des scénarios',
            'seuil-composante-edition'          => 'Édition des seuil (composantes)',
            'seuil-composante-visualisation'    => 'Visualisation des seuils (composantes)',
            'seuil-etablissement-edition'       => 'Édition des seuil (établissement)',
            'seuil-etablissement-visualisation' => 'Visualisation des seuils (établissement)',
            'visualisation'                     => 'Visualisation',
            'scenario-duplication'              => 'Duplication de scénario',
            'formation-actif-edition'           => 'Édition des formations (activation liens)',
            'formation-choix-edition'           => 'Édition des formations (choix liens)',
            'formation-poids-edition'           => 'Édition des formations (poids liens)',
            'export-csv'                        => 'Export CSV',
            'depassement-csv'                   => 'Dépassement services/charges (CSV)',
        ],
    ],

    'etat-sortie' => [
        'libelle'    => 'Etats de sortie',
        'privileges' => [
            'administration-visualisation' => 'Administration (visualisation)',
            'administration-edition'       => 'Administration (édition)',
        ],
    ],

    'parametres' => [
        'libelle'    => 'Paramétrages',
        'privileges' => [
            'general-edition'                           => 'Général - Édition',
            'general-visualisation'                     => 'Général - Visualisation',
            'campagnes-saisie-edition'                  => 'Campagnes de saisie - Édition',
            'campagnes-saisie-visualisation'            => 'Campagnes de saisie - Visualisation',
            'regles-structure-validation-visualisation' => 'Règles de validation - Visualisation',
            'regles-structure-validation-edition'       => 'Règles de validation - Édition',
            'annees-edition'                            => 'Années - Édition',
            'annees-visualisation'                      => 'Années - Visualisation',
            'periodes-edition'                          => 'Périodes - Édition',
            'periodes-visualisation'                    => 'Périodes - Visualisation',
            'etablissement-edition'                     => 'Établissement - Édition',
            'etablissement-visualisation'               => 'Établissement - Visualisation',
            'Pays-visualisation'                        => 'Pays - Visualisation',
            'Pays-edition'                              => 'Pays - Édition',
            'Departement-visualisation'                 => 'Departement - Visualisation',
            'Departement-edition'                       => 'Departement - Édition',
        ],
    ],

    'cloture' => [
        'libelle'    => 'Clôture des services réalisés',
        'privileges' => [
            'cloture'                   => 'Clôture',
            'reouverture'               => 'Réouverture',
            'edition-services'          => 'Modification des services après clôture',
            'edition-services-avec-mep' => 'Modification des services après clôture et mises en paiement',
        ],
    ],

    'structures' => [
        'libelle'    => 'Structures',
        'privileges' => [
            'administration-visualisation' => 'Administration (visualisation)',
            'administration-edition'       => 'Administration (édition)',
        ],
    ],

    'motifs-modification-service-du' => [
        'libelle'    => 'Motifs de modification de service dû',
        'privileges' => [
            'visualisation' => 'Administration (visualisation)',
            'edition'       => 'Administration (édition)',
        ],
    ],

    'domaines-fonctionnels' => [
        'libelle'    => 'Domaines fonctionnels',
        'privileges' => [
            'administration-visualisation' => 'Administration (visualisation)',
            'administration-edition'       => 'Administration (édition)',
        ],
    ],

    'centres-couts' => [
        'libelle'    => 'Paramétrage des centres de coûts',
        'privileges' => [
            'administration-visualisation' => 'Administration (visualisation)',
            'administration-edition'       => 'Administration (édition)',
            'administration-reconduction'  => 'Administration (reconduction)',
        ],
    ],

    'jour-ferie' => [
        'libelle'    => 'Paramétrage des jours fériés',
        'privileges' => [
            'visualisation' => 'Administration (visualisation)',
            'edition'       => 'Administration (édition)',
        ],
    ],

    'workflow' => [
        'libelle'    => 'Gestion du Workflow',
        'privileges' => [
            'dependances-visualisation' => 'Dépendances (visualisation)',
            'dependances-edition'       => 'Dépendances (édition)',
        ],
    ],

    'plafonds' => [
        'libelle'    => 'Administration des Plafonds',
        'privileges' => [
            'visualisation'             => 'Visualisation',
            'edition'                   => 'Édition',
            'config-structure'          => 'Configuration par composante',
            'config-referentiel'        => 'Configuration par fonction référentielle',
            'config-mission'            => 'Configuration par type de mission',
            'config-statut'             => 'Configuration par statut d\'intervenant',
            'derogations-visualisation' => 'Dérogations (visualisation)',
            'derogations-edition'       => 'Dérogations (édition)',
        ],
    ],

    'formule' => [
        'libelle'    => 'Formule de calcul',
        'privileges' => [
            'administration-visualisation' => 'Visualisation administration',
            'administration-edition'       => 'Édition administration',
            'tests'                        => 'Tests',
        ],
    ],

    'referentiel-commun' => [
        'libelle'    => 'Référentiels communs',
        'privileges' => [
            'voirie-visualisation'    => 'Visualisation voiries',
            'voirie-edition'          => 'Édition voiries',
            'employeur-visualisation' => 'Visualisation employeurs',
            'employeur-edition'       => 'Édition employeurs',
        ],
    ],

    'nomenclature-rh' => [
        'libelle'    => 'Nomenclature RH',
        'privileges' => [
            'grades-visualisation' => 'Visualisation grades',
            'grades-edition'       => 'Édition grades',
            'corps-visualisation'  => 'Visualisation corps',
            'corps-edition'        => 'Édition corps',
        ],
    ],

    'taux'      => [
        'libelle'    => 'Taux',
        'privileges' => [
            'visualisation' => 'Visualisation des taux',
            'edition'       => 'Edition des taux',
            'suppression'   => 'Suppression des taux',
        ],
    ],
    'mail'      => [
        'libelle'    => 'UnicaenMail - Gestion des mails',
        'privileges' => [
            'mail_index'     => 'Afficher un mail',
            'mail_afficher'  => 'Ré-envoi d\'un mail',
            'mail_supprimer' => 'Suppression d\'un mail',
            'mail_test'      => 'Envoi d\'un mail de test',
        ],
    ],
    'signature' => [
        'libelle'    => 'Signature',
        'privileges' => [
            'flow-edition' => 'Administrer les circuits de signatures',
        ],
    ],


];