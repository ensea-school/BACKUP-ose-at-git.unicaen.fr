<?php

use Service\Entity\Db\EtatVolumeHoraire;

return [
    'ADRESSE_NUMERO_COMPL' => [
        [
            'ID'      => 2,
            'CODE'    => 'B',
            'LIBELLE' => 'BIS',
            'CODE_RH' => 'B',

        ],
        [
            'ID'      => 3,
            'CODE'    => 'T',
            'LIBELLE' => 'TER',
            'CODE_RH' => 'T',
        ],
        [
            'ID'      => 4,
            'CODE'    => 'Q',
            'LIBELLE' => 'QUATER',
            'CODE_RH' => 'Q',
        ],
        [
            'ID'      => 5,
            'CODE'    => 'C',
            'LIBELLE' => 'QUINQUIES',
            'CODE_RH' => 'C',
        ],
    ],

    'AFFECTATION' => [
        [
            'UTILISATEUR_ID' => 'oseappli',
            'ROLE_ID'        => 'administrateur',
            'SOURCE_CODE'    => 'oseappli_default_aff',
        ],
    ],

    'CC_ACTIVITE' => [
        [
            'ID'          => 1,
            "CODE"        => "pilotage",
            "LIBELLE"     => "Pilotage",
            "FI"          => false,
            "FA"          => false,
            "FC"          => false,
            "PRIMES"      => false,
            "REFERENTIEL" => true,
            "MISSION"     => false,
        ],
        [
            'ID'          => 2,
            "CODE"        => "enseignement",
            "LIBELLE"     => "Enseignement",
            "FI"          => true,
            "FA"          => true,
            "FC"          => true,
            "PRIMES"      => true,
            "REFERENTIEL" => false,
            "MISSION"     => false,
        ],
        [
            'ID'          => 3,
            "CODE"        => "accueil",
            "LIBELLE"     => "Accueil",
            "FI"          => true,
            "FA"          => true,
            "FC"          => true,
            "PRIMES"      => true,
            "REFERENTIEL" => false,
            "MISSION"     => true,
        ],
    ],

    'CIVILITE' => [
        [
            'ID'            => 1,
            'LIBELLE_COURT' => 'Mme',
            'LIBELLE_LONG'  => 'Madame',
            'SEXE'          => 'F',
        ],
        [
            'ID'            => 2,
            'LIBELLE_COURT' => 'M.',
            'LIBELLE_LONG'  => 'Monsieur',
            'SEXE'          => 'M',
        ],
    ],

    'DOSSIER_CHAMP_AUTRE_TYPE' => [
        [
            'ID'      => 1,
            'CODE'    => 'texte',
            'LIBELLE' => 'Champ texte simple',
        ],
        [
            'ID'      => 2,
            'CODE'    => 'select-fixe',
            'LIBELLE' => 'Liste déroulante à valeurs constantes',
        ],
        [
            'ID'      => 3,
            'CODE'    => 'select-sql',
            'LIBELLE' => 'Liste déroulante basée sur une requête SQL',
        ],
    ],

    'DOSSIER_CHAMP_AUTRE' => [
        ['ID'                          => 1,
         'LIBELLE'                     => 'Dossier champ autre 1',
         'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1,
         'OBLIGATOIRE'                 => true],
        ['ID'                          => 2,
         'LIBELLE'                     => 'Dossier champ autre 2',
         'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1,
         'OBLIGATOIRE'                 => true],
        ['ID'                          => 3,
         'LIBELLE'                     => 'Dossier champ autre 3',
         'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1,
         'OBLIGATOIRE'                 => true],
        ['ID'                          => 4,
         'LIBELLE'                     => 'Dossier champ autre 4',
         'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1,
         'OBLIGATOIRE'                 => true],
        ['ID'                          => 5,
         'LIBELLE'                     => 'Dossier champ autre 5',
         'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1,
         'OBLIGATOIRE'                 => true],
    ],


    'ETAT_VOLUME_HORAIRE' => [
        [
            'ID'      => 1,
            'CODE'    => EtatVolumeHoraire::CODE_SAISI,
            'LIBELLE' => 'Saisi',
            'ORDRE'   => EtatVolumeHoraire::ORDRE_SAISI,
        ],
        [
            'ID'      => 2,
            'CODE'    => EtatVolumeHoraire::CODE_VALIDE,
            'LIBELLE' => 'Validé',
            'ORDRE'   => 2,
        ],
        [
            'ID'      => 3,
            'CODE'    => EtatVolumeHoraire::CODE_CONTRAT_EDITE,
            'LIBELLE' => 'Contrat édité',
            'ORDRE'   => 3,
        ],
        [
            'ID'      => 4,
            'CODE'    => EtatVolumeHoraire::CODE_CONTRAT_SIGNE,
            'LIBELLE' => 'Contrat signé',
            'ORDRE'   => 4,
        ],
    ],


    'PERIMETRE' => [
        [
            'ID'      => 1,
            "CODE"    => "composante",
            "LIBELLE" => "Composante",
        ],
        /*[
            'ID'      => 2,
            "CODE"    => "diplome",
            "LIBELLE" => "Diplôme",
        ],*/
        [
            'ID'      => 3,
            "CODE"    => "etablissement",
            "LIBELLE" => "Établissement",
        ],
    ],

    "PERIODE" => [
        [
            "CODE"          => "P01",
            "LIBELLE_LONG"  => "Septembre",
            "LIBELLE_COURT" => "Septembre",
            "ORDRE"         => 1,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 0,
        ],
        [
            "CODE"          => "P02",
            "LIBELLE_LONG"  => "Octobre",
            "LIBELLE_COURT" => "Octobre",
            "ORDRE"         => 2,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 1,
        ],
        [
            "CODE"          => "P03",
            "LIBELLE_LONG"  => "Novembre",
            "LIBELLE_COURT" => "Novembre",
            "ORDRE"         => 3,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 2,
        ],
        [
            "CODE"          => "P04",
            "LIBELLE_LONG"  => "Décembre",
            "LIBELLE_COURT" => "Décembre",
            "ORDRE"         => 4,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 3,
        ],
        [
            "CODE"          => "P05",
            "LIBELLE_LONG"  => "Janvier",
            "LIBELLE_COURT" => "Janvier",
            "ORDRE"         => 5,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 4,
        ],
        [
            "CODE"          => "P06",
            "LIBELLE_LONG"  => "Février",
            "LIBELLE_COURT" => "Février",
            "ORDRE"         => 6,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 5,
        ],
        [
            "CODE"          => "P07",
            "LIBELLE_LONG"  => "Mars",
            "LIBELLE_COURT" => "Mars",
            "ORDRE"         => 7,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 6,
        ],
        [
            "CODE"          => "P08",
            "LIBELLE_LONG"  => "Avril",
            "LIBELLE_COURT" => "Avril",
            "ORDRE"         => 8,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 7,
        ],
        [
            "CODE"          => "P09",
            "LIBELLE_LONG"  => "Mai",
            "LIBELLE_COURT" => "Mai",
            "ORDRE"         => 9,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 8,
        ],
        [
            "CODE"          => "P10",
            "LIBELLE_LONG"  => "Juin",
            "LIBELLE_COURT" => "Juin",
            "ORDRE"         => 10,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 9,
        ],
        [
            "CODE"          => "P11",
            "LIBELLE_LONG"  => "Juillet",
            "LIBELLE_COURT" => "Juillet",
            "ORDRE"         => 11,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 10,
        ],
        [
            "CODE"          => "P12",
            "LIBELLE_LONG"  => "Août",
            "LIBELLE_COURT" => "Août",
            "ORDRE"         => 12,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 11,
        ],
        [
            "CODE"          => "P13",
            "LIBELLE_LONG"  => "Septembre",
            "LIBELLE_COURT" => "Septembre",
            "ORDRE"         => 13,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 12,
        ],
        [
            "CODE"          => "P14",
            "LIBELLE_LONG"  => "Octobre",
            "LIBELLE_COURT" => "Octobre",
            "ORDRE"         => 14,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 13,
        ],
        [
            "CODE"          => "P15",
            "LIBELLE_LONG"  => "Novembre",
            "LIBELLE_COURT" => "Novembre",
            "ORDRE"         => 15,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 14,
        ],
        [
            "CODE"          => "P16",
            "LIBELLE_LONG"  => "Décembre",
            "LIBELLE_COURT" => "Décembre",
            "ORDRE"         => 16,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 15,
        ],
        [
            "CODE"          => "PTD",
            "LIBELLE_LONG"  => "Paiement tardif",
            "LIBELLE_COURT" => "Paiement tardif",
            "ORDRE"         => 17,
            "ENSEIGNEMENT"  => false,
            "PAIEMENT"      => true,
            "ECART_MOIS"    => 16,
        ],
        [
            "CODE"          => "S1",
            "LIBELLE_LONG"  => "Semestre 1",
            "LIBELLE_COURT" => "S1",
            "ORDRE"         => 18,
            "ENSEIGNEMENT"  => true,
            "PAIEMENT"      => false,
            "ECART_MOIS"    => 0,
        ],
        [
            "CODE"          => "S2",
            "LIBELLE_LONG"  => "Semestre 2",
            "LIBELLE_COURT" => "S2",
            "ORDRE"         => 19,
            "ENSEIGNEMENT"  => true,
            "PAIEMENT"      => false,
            "ECART_MOIS"    => 5,
        ],
    ],

    'REGLE_STRUCTURE_VALIDATION' => [
        [
            'ID'                     => 1,
            "TYPE_VOLUME_HORAIRE_ID" => 1,
            "TYPE_INTERVENANT_ID"    => 1,
            "PRIORITE"               => "affectation",
            'MESSAGE'                => null,
        ],
        [
            'ID'                     => 2,
            "TYPE_VOLUME_HORAIRE_ID" => 1,
            "TYPE_INTERVENANT_ID"    => 2,
            "PRIORITE"               => "enseignement",
            'MESSAGE'                => null,
        ],
        [
            'ID'                     => 3,
            "TYPE_VOLUME_HORAIRE_ID" => 2,
            "TYPE_INTERVENANT_ID"    => 1,
            "PRIORITE"               => "enseignement",
            'MESSAGE'                => null,
        ],
        [
            'ID'                     => 4,
            "TYPE_VOLUME_HORAIRE_ID" => 2,
            "TYPE_INTERVENANT_ID"    => 2,
            "PRIORITE"               => "enseignement",
            'MESSAGE'                => null,
        ],
    ],

    "ROLE" => [
        [
            "CODE"                   => "administrateur",
            "LIBELLE"                => "Administrateur",
            "PERIMETRE_ID"           => 'etablissement',
            "PEUT_CHANGER_STRUCTURE" => true,
        ],
        [
            "CODE"                   => "gestionnaire-composante",
            "LIBELLE"                => "Gestionnaire polyvalent",
            "PERIMETRE_ID"           => 'composante',
            "PEUT_CHANGER_STRUCTURE" => false,
        ],
        [
            "CODE"                   => "superviseur-etablissement",
            "LIBELLE"                => "Superviseur (Pilotage et direction)",
            "PERIMETRE_ID"           => 'etablissement',
            "PEUT_CHANGER_STRUCTURE" => false,
        ],
    ],

    'SCENARIO' => [
        [
            "LIBELLE" => "Initial",
            "TYPE"    => 1,
        ],
    ],

    "SITUATION_MATRIMONIALE" => [
        [
            "LIBELLE" => "Célibataire",
            "CODE"    => "CEL",
        ],
        [
            "LIBELLE" => "Divorcé(e)",
            "CODE"    => "DIV",
        ],
        [
            "LIBELLE" => "En concubinage",
            "CODE"    => "CCB",
        ],
        [
            "LIBELLE" => "Marié(e)",
            "CODE"    => "MAR",
        ],
        [
            "LIBELLE" => "Pacsé(e)",
            "CODE"    => "PAC",
        ],
        [
            "LIBELLE" => "Séparé(e) de corps",
            "CODE"    => "SEC",
        ],
        [
            "LIBELLE" => "Séparé(e) de fait",
            "CODE"    => "SEP",
        ],
        [
            "LIBELLE" => "Veuf(ve)",
            "CODE"    => "VEU",
        ],

    ],

    'SOURCE' => [
        [
            'CODE'       => 'OSE',
            'LIBELLE'    => 'OSE',
            'IMPORTABLE' => false,
        ],
        [
            'CODE'       => 'Calcul',
            'LIBELLE'    => 'Calculée',
            'IMPORTABLE' => true,
        ],
    ],

    'TYPE_AGREMENT' => [
        [
            'ID'      => 1,
            "CODE"    => "CONSEIL_RESTREINT",
            "LIBELLE" => "Conseil Restreint",
        ],
        [
            'ID'      => 2,
            "CODE"    => "CONSEIL_ACADEMIQUE",
            "LIBELLE" => "Conseil Académique",
        ],
    ],

    'TYPE_CONTRAT' => [
        [
            'ID'      => 1,
            "CODE"    => "CONTRAT",
            "LIBELLE" => "Contrat",
        ],
        [
            'ID'      => 2,
            "CODE"    => "AVENANT",
            "LIBELLE" => "Avenant",
        ],
    ],


    'TYPE_HEURES' => [
        [
            "CODE"                     => "fi",
            "LIBELLE_COURT"            => "Fi",
            "LIBELLE_LONG"             => "Formation initiale",
            "ORDRE"                    => 1,
            "TYPE_HEURES_ELEMENT_ID"   => 1,
            "ELIGIBLE_CENTRE_COUT_EP"  => true,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => true,
        ],
        [
            "CODE"                     => "fa",
            "LIBELLE_COURT"            => "Fa",
            "LIBELLE_LONG"             => "Formation en apprentissage",
            "ORDRE"                    => 2,
            "TYPE_HEURES_ELEMENT_ID"   => 2,
            "ELIGIBLE_CENTRE_COUT_EP"  => true,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => true,
        ],
        [
            "CODE"                     => "fc",
            "LIBELLE_COURT"            => "Fc",
            "LIBELLE_LONG"             => "Formation continue",
            "ORDRE"                    => 3,
            "TYPE_HEURES_ELEMENT_ID"   => 3,
            "ELIGIBLE_CENTRE_COUT_EP"  => true,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => true,
        ],
        [
            "CODE"                     => "primes",
            "LIBELLE_COURT"            => "Rém. FC D714-60",
            "LIBELLE_LONG"             => "Rémunération de la formation continue au titre de l'article D714-60",
            "ORDRE"                    => 4,
            "TYPE_HEURES_ELEMENT_ID"   => 3,
            "ELIGIBLE_CENTRE_COUT_EP"  => false,
            "ELIGIBLE_EXTRACTION_PAIE" => false,
            "ENSEIGNEMENT"             => false,
        ],
        [
            "CODE"                     => "referentiel",
            "LIBELLE_COURT"            => "Référentiel",
            "LIBELLE_LONG"             => "Référentiel",
            "ORDRE"                    => 5,
            "TYPE_HEURES_ELEMENT_ID"   => 5,
            "ELIGIBLE_CENTRE_COUT_EP"  => false,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => false,
        ],
        [
            "CODE"                     => "mission",
            "LIBELLE_COURT"            => "Mission",
            "LIBELLE_LONG"             => "Mission",
            "ORDRE"                    => 6,
            "TYPE_HEURES_ELEMENT_ID"   => 6,
            "ELIGIBLE_CENTRE_COUT_EP"  => false,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => false,
        ],
        [
            "CODE"                     => "enseignement",
            "LIBELLE_COURT"            => "Enseignement",
            "LIBELLE_LONG"             => "Enseignement",
            "ORDRE"                    => 7,
            "TYPE_HEURES_ELEMENT_ID"   => 7,
            "ELIGIBLE_CENTRE_COUT_EP"  => false,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => true,
        ],
    ],

    'TYPE_INTERVENANT' => [
        [
            'ID'      => 1,
            "CODE"    => "P",
            "LIBELLE" => "Intervenant permanent",
        ],
        [
            'ID'      => 2,
            "CODE"    => "E",
            "LIBELLE" => "Vacataire",
        ],
        [
            'ID'      => 3,
            "CODE"    => "S",
            "LIBELLE" => "Étudiant",
        ],
    ],

    'TYPE_INTERVENTION' => [
        [
            'ID'                       => 1,
            "CODE"                     => "CM",
            "LIBELLE"                  => "Cours magistraux",
            "ORDRE"                    => 1,
            "TAUX_HETD_SERVICE"        => 1.5,
            "TAUX_HETD_COMPLEMENTAIRE" => 1.5,
            "VISIBLE"                  => true,
            "REGLE_FOAD"               => false,
            "REGLE_FC"                 => false,
            "VISIBLE_EXTERIEUR"        => true,
        ],
        [
            'ID'                       => 2,
            "CODE"                     => "TD",
            "LIBELLE"                  => "Travaux dirigés",
            "ORDRE"                    => 2,
            "TAUX_HETD_SERVICE"        => 1.0,
            "TAUX_HETD_COMPLEMENTAIRE" => 1.0,
            "VISIBLE"                  => true,
            "REGLE_FOAD"               => false,
            "REGLE_FC"                 => false,
            "VISIBLE_EXTERIEUR"        => true,
        ],
        [
            'ID'                       => 3,
            "CODE"                     => "TP",
            "LIBELLE"                  => "Travaux pratiques",
            "ORDRE"                    => 3,
            "TAUX_HETD_SERVICE"        => 1.0,
            "TAUX_HETD_COMPLEMENTAIRE" => 2 / 3,
            "VISIBLE"                  => true,
            "REGLE_FOAD"               => false,
            "REGLE_FC"                 => false,
            "VISIBLE_EXTERIEUR"        => true,
        ],
    ],
    "TYPE_NOTE"         => [
        [
            "ID"      => 1,
            "LIBELLE" => "Email",
            "CODE"    => "email",
        ],
        [
            "ID"      => 2,
            "LIBELLE" => "Note",
            "CODE"    => "note",
        ],


    ],

    'TYPE_RESSOURCE' => [
        [
            'ID'            => 1,
            "CODE"          => "paie-etat",
            "LIBELLE"       => "Paie Etat",
            "FI"            => true,
            "FA"            => false,
            "FC"            => false,
            "PRIMES"        => false,
            "REFERENTIEL"   => true,
            "ETABLISSEMENT" => true,
        ],
        [
            'ID'            => 2,
            "CODE"          => "ressources-propres",
            "LIBELLE"       => "Ressources propres",
            "FI"            => true,
            "FA"            => true,
            "FC"            => true,
            "PRIMES"        => true,
            "REFERENTIEL"   => true,
            "ETABLISSEMENT" => false,
        ],
    ],

    'TYPE_VALIDATION' => [
        [
            'ID'      => 1,
            "CODE"    => "DONNEES_PERSO_PAR_COMP",
            "LIBELLE" => "Validation des données personnelles",
        ],
        [
            'ID'      => 2,
            "CODE"    => "SERVICES_PAR_COMP",
            "LIBELLE" => "Validation des enseignements",
        ],
        [
            'ID'      => 3,
            "CODE"    => "CONTRAT_PAR_COMP",
            "LIBELLE" => "Validation du contrat ou avenant",
        ],
        [
            'ID'      => 4,
            "CODE"    => "PIECE_JOINTE",
            "LIBELLE" => "Validation de pièce justificative",
        ],
        [
            'ID'      => 5,
            "CODE"    => "FICHIER",
            "LIBELLE" => "Validation de fichier",
        ],
        [
            'ID'      => 6,
            "CODE"    => "REFERENTIEL",
            "LIBELLE" => "Validation du référentiel",
        ],
        [
            'ID'      => 7,
            "CODE"    => "CLOTURE_REALISE",
            "LIBELLE" => "Clôture de la saisie des enseignements réalisés",
        ],
        [
            'ID'      => 8,
            "CODE"    => "MISSION",
            "LIBELLE" => "Validation de mission par la DRH",
        ],
        [
            'ID'      => 9,
            "CODE"    => "MISSION_REALISE",
            "LIBELLE" => "Validation d'heures de mission réalisées",
        ],
        [
            'ID'      => 10,
            "CODE"    => "OFFRE_EMPLOI",
            "LIBELLE" => "Validation d'une offre d'emploi par la DRH",
        ],
        [
            'ID'      => 11,
            "CODE"    => "CANDIDATURE",
            "LIBELLE" => "Validation d'une candidature par la DRH",
        ],
        [
            'ID'      => 12,
            "CODE"    => "DECLARATION_PRIME",
            "LIBELLE" => "Validation d'une déclaration prime",
        ],
        [
            'ID'      => 13,
            "CODE"    => "DONNEES_PERSO_COMPLEMENTAIRE_PAR_COMP",
            "LIBELLE" => "Validation des données personnelles",
        ],
    ],

    'TYPE_VOLUME_HORAIRE' => [
        [
            'ID'      => 1,
            'CODE'    => 'PREVU',
            'LIBELLE' => 'Prévisionnel',
            'ORDRE'   => 1,
        ],
        [
            'ID'      => 2,
            'CODE'    => 'REALISE',
            'LIBELLE' => 'Réalisé',
            'ORDRE'   => 2,
        ],
    ],

    'TYPE_SERVICE' => [
        [
            'ID'      => 1,
            'CODE'    => 'ENS',
            'LIBELLE' => 'Enseignement',
        ],
        [
            'ID'      => 2,
            'CODE'    => 'REF',
            'LIBELLE' => 'Référentiel',
        ],
        [
            'ID'      => 3,
            'CODE'    => 'MIS',
            'LIBELLE' => 'Mission',
        ],
    ],

    'UTILISATEUR' => [
        [
            'USERNAME'             => 'oseappli',
            'EMAIL'                => 'votre_mail@votre_etablissement.fr',
            'DISPLAY_NAME'         => 'Application OSE',
            'PASSWORD'             => 'x',
            'STATE'                => 1,
            'CODE'                 => null,
            'PASSWORD_RESET_TOKEN' => null,
        ],
    ],
];