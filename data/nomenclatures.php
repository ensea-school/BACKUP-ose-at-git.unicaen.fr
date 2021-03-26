<?php

return [
    'ADRESSE_NUMERO_COMPL' => [
        [
            'ID'      => 2,
            'CODE'    => 'B',
            'LIBELLE' => 'BIS',
        ],
        [
            'ID'      => 3,
            'CODE'    => 'T',
            'LIBELLE' => 'TER',
        ],
        [
            'ID'      => 4,
            'CODE'    => 'Q',
            'LIBELLE' => 'QUATER',
        ],
        [
            'ID'      => 5,
            'CODE'    => 'C',
            'LIBELLE' => 'QUINQUIES',
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
            "FC_MAJOREES" => false,
            "REFERENTIEL" => true,
        ],
        [
            'ID'          => 2,
            "CODE"        => "enseignement",
            "LIBELLE"     => "Enseignement",
            "FI"          => true,
            "FA"          => true,
            "FC"          => true,
            "FC_MAJOREES" => true,
            "REFERENTIEL" => false,
        ],
        [
            'ID'          => 3,
            "CODE"        => "accueil",
            "LIBELLE"     => "Accueil",
            "FI"          => true,
            "FA"          => true,
            "FC"          => true,
            "FC_MAJOREES" => true,
            "REFERENTIEL" => false,
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
        ['ID' => 1, 'LIBELLE' => 'Dossier champs autre 1', 'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1, 'OBLIGATOIRE' => true],
        ['ID' => 2, 'LIBELLE' => 'Dossier champs autre 2', 'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1, 'OBLIGATOIRE' => true],
        ['ID' => 3, 'LIBELLE' => 'Dossier champs autre 3', 'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1, 'OBLIGATOIRE' => true],
        ['ID' => 4, 'LIBELLE' => 'Dossier champs autre 4', 'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1, 'OBLIGATOIRE' => true],
        ['ID' => 5, 'LIBELLE' => 'Dossier champs autre 5', 'DOSSIER_CHAMP_AUTRE_TYPE_ID' => 1, 'OBLIGATOIRE' => true],
    ],


    'ETAT_VOLUME_HORAIRE' => [
        [
            'ID'      => 1,
            'CODE'    => 'saisi',
            'LIBELLE' => 'Saisi',
            'ORDRE'   => 1,
        ],
        [
            'ID'      => 2,
            'CODE'    => 'valide',
            'LIBELLE' => 'Validé',
            'ORDRE'   => 2,
        ],
        [
            'ID'      => 3,
            'CODE'    => 'contrat-edite',
            'LIBELLE' => 'Contrat édité',
            'ORDRE'   => 3,
        ],
        [
            'ID'      => 4,
            'CODE'    => 'contrat-signe',
            'LIBELLE' => 'Contrat signé',
            'ORDRE'   => 4,
        ],
    ],

    'FORMULE' => [
        1  => [
            'LIBELLE'      => 'Université de Caen',
            'PACKAGE_NAME' => 'FORMULE_UNICAEN',
        ],
        2  => [
            'LIBELLE'      => 'Université de Montpellier',
            'PACKAGE_NAME' => 'FORMULE_MONTPELLIER',
        ],
        3  => [
            'LIBELLE'      => 'Université Le Havre Normandie',
            'PACKAGE_NAME' => 'FORMULE_ULHN',
        ],
        4  => [
            'LIBELLE'      => 'Université de Nanterre',
            'PACKAGE_NAME' => 'FORMULE_NANTERRE',
        ],
        5  => [
            'LIBELLE'           => 'Université de Bretagne Occidentale',
            'PACKAGE_NAME'      => 'FORMULE_UBO',
            'I_PARAM_1_LIBELLE' => 'EC/Associé/Docteur',
            'I_PARAM_2_LIBELLE' => 'Lecteur/ATER',
        ],
        6  => [
            'LIBELLE'      => 'Ensicaen',
            'PACKAGE_NAME' => 'FORMULE_ENSICAEN',
        ],
        7  => [
            'LIBELLE'      => 'Université Lumière Lyon 2',
            'PACKAGE_NAME' => 'FORMULE_LYON2',
        ],
        8  => [
            'LIBELLE'      => 'Université Jean Monnet (Saint-Étienne)',
            'PACKAGE_NAME' => 'FORMULE_ST_ETIENNE',
        ],
        9  => [
            'LIBELLE'      => 'Université Côté d\'azur',
            'PACKAGE_NAME' => 'FORMULE_COTE_AZUR',
        ],
        10 => [
            'LIBELLE'      => 'Université Rennes 2',
            'PACKAGE_NAME' => 'FORMULE_RENNES2',
        ],
        11 => [
            'LIBELLE'      => 'INSA de Lyon',
            'PACKAGE_NAME' => 'FORMULE_INSA_LYON',
        ],
        12 => [
            'LIBELLE'           => 'Université de Poitiers',
            'PACKAGE_NAME'      => 'FORMULE_POITIERS',
            'I_PARAM_1_LIBELLE' => 'Heures max. référentiel en service',
            'I_PARAM_2_LIBELLE' => 'Heures max. référentiel en HC',
            'I_PARAM_3_LIBELLE' => 'Heures max. enseignement en HC',
        ],
        13 => [
            'LIBELLE'      => 'Université Paris 8',
            'PACKAGE_NAME' => 'FORMULE_PARIS8',
        ],
        14 => [
            'LIBELLE'      => 'Université d\'Artois',
            'PACKAGE_NAME' => 'FORMULE_ARTOIS',
        ],
    ],

    'MODELE_CONTRAT' => [
        [
            'ID'      => 1,
            'LIBELLE' => 'Modèle par défaut',
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
            "CODE"                => "P01",
            "LIBELLE_LONG"        => "Septembre",
            "LIBELLE_COURT"       => "Septembre",
            "ORDRE"               => 1,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 0,
            "ECART_MOIS_PAIEMENT" => -1,
        ],
        [
            "CODE"                => "P02",
            "LIBELLE_LONG"        => "Octobre",
            "LIBELLE_COURT"       => "Octobre",
            "ORDRE"               => 2,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 1,
            "ECART_MOIS_PAIEMENT" => 0,
        ],
        [
            "CODE"                => "P03",
            "LIBELLE_LONG"        => "Novembre",
            "LIBELLE_COURT"       => "Novembre",
            "ORDRE"               => 3,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 2,
            "ECART_MOIS_PAIEMENT" => 1,
        ],
        [
            "CODE"                => "P04",
            "LIBELLE_LONG"        => "Décembre",
            "LIBELLE_COURT"       => "Décembre",
            "ORDRE"               => 4,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 3,
            "ECART_MOIS_PAIEMENT" => 2,
        ],
        [
            "CODE"                => "P05",
            "LIBELLE_LONG"        => "Janvier",
            "LIBELLE_COURT"       => "Janvier",
            "ORDRE"               => 5,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 4,
            "ECART_MOIS_PAIEMENT" => 3,
        ],
        [
            "CODE"                => "P06",
            "LIBELLE_LONG"        => "Février",
            "LIBELLE_COURT"       => "Février",
            "ORDRE"               => 6,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 5,
            "ECART_MOIS_PAIEMENT" => 4,
        ],
        [
            "CODE"                => "P07",
            "LIBELLE_LONG"        => "Mars",
            "LIBELLE_COURT"       => "Mars",
            "ORDRE"               => 7,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 6,
            "ECART_MOIS_PAIEMENT" => 5,
        ],
        [
            "CODE"                => "P08",
            "LIBELLE_LONG"        => "Avril",
            "LIBELLE_COURT"       => "Avril",
            "ORDRE"               => 8,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 7,
            "ECART_MOIS_PAIEMENT" => 6,
        ],
        [
            "CODE"                => "P09",
            "LIBELLE_LONG"        => "Mai",
            "LIBELLE_COURT"       => "Mai",
            "ORDRE"               => 9,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 8,
            "ECART_MOIS_PAIEMENT" => 7,
        ],
        [
            "CODE"                => "P10",
            "LIBELLE_LONG"        => "Juin",
            "LIBELLE_COURT"       => "Juin",
            "ORDRE"               => 10,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 9,
            "ECART_MOIS_PAIEMENT" => 8,
        ],
        [
            "CODE"                => "P11",
            "LIBELLE_LONG"        => "Juillet",
            "LIBELLE_COURT"       => "Juillet",
            "ORDRE"               => 11,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 10,
            "ECART_MOIS_PAIEMENT" => 9,
        ],
        [
            "CODE"                => "P12",
            "LIBELLE_LONG"        => "Août",
            "LIBELLE_COURT"       => "Août",
            "ORDRE"               => 12,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 11,
            "ECART_MOIS_PAIEMENT" => 10,
        ],
        [
            "CODE"                => "P13",
            "LIBELLE_LONG"        => "Septembre",
            "LIBELLE_COURT"       => "Septembre",
            "ORDRE"               => 13,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 12,
            "ECART_MOIS_PAIEMENT" => 11,
        ],
        [
            "CODE"                => "P14",
            "LIBELLE_LONG"        => "Octobre",
            "LIBELLE_COURT"       => "Octobre",
            "ORDRE"               => 14,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 13,
            "ECART_MOIS_PAIEMENT" => 12,
        ],
        [
            "CODE"                => "P15",
            "LIBELLE_LONG"        => "Novembre",
            "LIBELLE_COURT"       => "Novembre",
            "ORDRE"               => 15,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 14,
            "ECART_MOIS_PAIEMENT" => 13,
        ],
        [
            "CODE"                => "P16",
            "LIBELLE_LONG"        => "Décembre",
            "LIBELLE_COURT"       => "Décembre",
            "ORDRE"               => 16,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 15,
            "ECART_MOIS_PAIEMENT" => 14,
        ],
        [
            "CODE"                => "PTD",
            "LIBELLE_LONG"        => "Paiement tardif",
            "LIBELLE_COURT"       => "Paiement tardif",
            "ORDRE"               => 17,
            "ENSEIGNEMENT"        => false,
            "PAIEMENT"            => true,
            "ECART_MOIS"          => 16,
            "ECART_MOIS_PAIEMENT" => 15,
        ],
        [
            "CODE"                => "S1",
            "LIBELLE_LONG"        => "Semestre 1",
            "LIBELLE_COURT"       => "S1",
            "ORDRE"               => 18,
            "ENSEIGNEMENT"        => true,
            "PAIEMENT"            => false,
            "ECART_MOIS"          => 0,
            "ECART_MOIS_PAIEMENT" => -1,
        ],
        [
            "CODE"                => "S2",
            "LIBELLE_LONG"        => "Semestre 2",
            "LIBELLE_COURT"       => "S2",
            "ORDRE"               => 19,
            "ENSEIGNEMENT"        => true,
            "PAIEMENT"            => false,
            "ECART_MOIS"          => 5,
            "ECART_MOIS_PAIEMENT" => 4,
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

    'TAUX_HORAIRE_HETD' => [
        [
            'ID'                 => 1,
            'VALEUR'             => 40.91,
            'HISTO_CREATION'     => '2010-07-01 00:00:00',
            'HISTO_MODIFICATION' => '2014-06-25 00:00:00',
        ],
        [
            'ID'                 => 2,
            'VALEUR'             => 41.41,
            'HISTO_CREATION'     => '2017-04-28 00:00:00',
            'HISTO_MODIFICATION' => '2017-04-28 00:00:00',
        ],
    ],

    'TBL' => [
        [
            'TBL_NAME'           => 'chargens_seuils_def',
            'TABLE_NAME'         => 'TBL_CHARGENS_SEUILS_DEF',
            'VIEW_NAME'          => 'V_TBL_CHARGENS_SEUILS_DEF',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_CHARGENS_SEUILS_DEF_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 1,
            'FEUILLE_DE_ROUTE'   => false,
        ],
        [
            'TBL_NAME'           => 'formule',
            'TABLE_NAME'         => null,
            'VIEW_NAME'          => null,
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => null,
            'CUSTOM_CALCUL_PROC' => 'OSE_FORMULE.CALCULER_TBL',
            'ORDRE'              => 1,
            'FEUILLE_DE_ROUTE'   => true,
            'PARAMETRES'         => 'INTERVENANT_ID,ANNEE_ID',
        ],
        [
            'TBL_NAME'           => 'chargens',
            'TABLE_NAME'         => 'TBL_CHARGENS',
            'VIEW_NAME'          => 'V_TBL_CHARGENS',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_CHARGENS_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 1,
            'FEUILLE_DE_ROUTE'   => false,
        ],
        [
            'TBL_NAME'           => 'dmep_liquidation',
            'TABLE_NAME'         => 'TBL_DMEP_LIQUIDATION',
            'VIEW_NAME'          => 'V_TBL_DMEP_LIQUIDATION',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_DMEP_LIQUIDATION_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 1,
            'FEUILLE_DE_ROUTE'   => false,
        ],
        [
            'TBL_NAME'           => 'piece_jointe_demande',
            'TABLE_NAME'         => 'TBL_PIECE_JOINTE_DEMANDE',
            'VIEW_NAME'          => 'V_TBL_PIECE_JOINTE_DEMANDE',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_PIECE_JOINTE_DEMANDE_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 2,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'piece_jointe_fournie',
            'TABLE_NAME'         => 'TBL_PIECE_JOINTE_FOURNIE',
            'VIEW_NAME'          => 'V_TBL_PIECE_JOINTE_FOURNIE',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_PIECE_JOINTE_FOURNIE_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 3,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'agrement',
            'TABLE_NAME'         => 'TBL_AGREMENT',
            'VIEW_NAME'          => 'V_TBL_AGREMENT',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_AGREMENT_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 4,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'cloture_realise',
            'TABLE_NAME'         => 'TBL_CLOTURE_REALISE',
            'VIEW_NAME'          => 'V_TBL_CLOTURE_REALISE',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_CLOTURE_REALISE_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 5,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'contrat',
            'TABLE_NAME'         => 'TBL_CONTRAT',
            'VIEW_NAME'          => 'V_TBL_CONTRAT',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_CONTRAT_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 6,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'dossier',
            'TABLE_NAME'         => 'TBL_DOSSIER',
            'VIEW_NAME'          => 'V_TBL_DOSSIER',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_DOSSIER_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 7,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'paiement',
            'TABLE_NAME'         => 'TBL_PAIEMENT',
            'VIEW_NAME'          => 'V_TBL_PAIEMENT',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_PAIEMENT_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 8,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'piece_jointe',
            'TABLE_NAME'         => 'TBL_PIECE_JOINTE',
            'VIEW_NAME'          => 'V_TBL_PIECE_JOINTE',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_PIECE_JOINTE_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 9,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'service_saisie',
            'TABLE_NAME'         => 'TBL_SERVICE_SAISIE',
            'VIEW_NAME'          => 'V_TBL_SERVICE_SAISIE',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_SERVICE_SAISIE_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 10,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'service_referentiel',
            'TABLE_NAME'         => 'TBL_SERVICE_REFERENTIEL',
            'VIEW_NAME'          => 'V_TBL_SERVICE_REFERENTIEL',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_SERVICE_REFERENTIEL_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 11,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'validation_enseignement',
            'TABLE_NAME'         => 'TBL_VALIDATION_ENSEIGNEMENT',
            'VIEW_NAME'          => 'V_TBL_VALIDATION_ENSEIGNEMENT',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_VALIDATION_ENSEIGNEMENT_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 12,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'validation_referentiel',
            'TABLE_NAME'         => 'TBL_VALIDATION_REFERENTIEL',
            'VIEW_NAME'          => 'V_TBL_VALIDATION_REFERENTIEL',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_VALIDATION_REFERENTIEL_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 13,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'service',
            'TABLE_NAME'         => 'TBL_SERVICE',
            'VIEW_NAME'          => 'V_TBL_SERVICE',
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => 'TBL_SERVICE_UN',
            'CUSTOM_CALCUL_PROC' => null,
            'ORDRE'              => 14,
            'FEUILLE_DE_ROUTE'   => true,
        ],
        [
            'TBL_NAME'           => 'workflow',
            'TABLE_NAME'         => null,
            'VIEW_NAME'          => null,
            'SEQUENCE_NAME'      => null,
            'CONSTRAINT_NAME'    => null,
            'CUSTOM_CALCUL_PROC' => 'OSE_WORKFLOW.CALCULER_TBL',
            'ORDRE'              => 15,
            'FEUILLE_DE_ROUTE'   => true,
            'PARAMETRES'         => 'INTERVENANT_ID,ANNEE_ID',
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
            'ID'                       => 1,
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
            'ID'                       => 2,
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
            'ID'                       => 3,
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
            'ID'                       => 4,
            "CODE"                     => "fc_majorees",
            "LIBELLE_COURT"            => "Rém. FC D714-60",
            "LIBELLE_LONG"             => "Rémunération de la formation continue au titre de l'article D714-60",
            "ORDRE"                    => 4,
            "TYPE_HEURES_ELEMENT_ID"   => 3,
            "ELIGIBLE_CENTRE_COUT_EP"  => false,
            "ELIGIBLE_EXTRACTION_PAIE" => false,
            "ENSEIGNEMENT"             => false,
        ],
        [
            'ID'                       => 5,
            "CODE"                     => "referentiel",
            "LIBELLE_COURT"            => "Référentiel",
            "LIBELLE_LONG"             => "Référentiel",
            "ORDRE"                    => 5,
            "TYPE_HEURES_ELEMENT_ID"   => 5,
            "ELIGIBLE_CENTRE_COUT_EP"  => false,
            "ELIGIBLE_EXTRACTION_PAIE" => true,
            "ENSEIGNEMENT"             => false,
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

    'TYPE_RESSOURCE' => [
        [
            'ID'            => 1,
            "CODE"          => "paie-etat",
            "LIBELLE"       => "Paie Etat",
            "FI"            => true,
            "FA"            => false,
            "FC"            => false,
            "FC_MAJOREES"   => false,
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
            "FC_MAJOREES"   => true,
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

    'UTILISATEUR' => [
        [
            'USERNAME'             => 'oseappli',
            'EMAIL'                => 'dsi.applications@unicaen.fr',
            'DISPLAY_NAME'         => 'Application OSE',
            'PASSWORD'             => 'x',
            'STATE'                => 1,
            'CODE'                 => null,
            'PASSWORD_RESET_TOKEN' => null,
        ],
    ],
];