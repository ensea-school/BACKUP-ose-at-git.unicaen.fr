<?php

return [
    /* Obligatoire au début */
    'UTILISATEUR'                => [
        'actions' => ['install', 'update'],
        'key'     => 'USERNAME',
        'options' => ['update-ignore-cols' => ['EMAIL', 'PASSWORD'], 'delete' => false],
    ],
    'SOURCE'                     => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
        'options' => ['delete' => false],
    ],


    /* Nomenclatures fixes et jamais paramétrables */
    'CIVILITE'                   => [
        'actions' => ['install'],
        'key'     => ['LIBELLE_COURT'],
    ],
    'SITUATION_MATRIMONIALE'     => [
        'actions' => ['install', 'update'],
        'key'     => ['CODE'],
    ],
    'PLAFOND_ETAT'               => [
        'actions' => ['install', 'update'],
        'key'     => 'ID',
    ],
    'PLAFOND_PERIMETRE'          => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'PLAFOND'                    => [
        'actions' => ['install', 'update'],
        'options' => ['update' => false, 'delete' => false],
        'key'     => 'NUMERO',
    ],
    'TYPE_NOTE'                  => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'TYPE_VOLUME_HORAIRE'        => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'ETAT_VOLUME_HORAIRE'        => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'PERIMETRE'                  => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'TYPE_VALIDATION'            => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'TYPE_AGREMENT'              => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'TYPE_CONTRAT'               => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'CATEGORIE_PRIVILEGE'        => [
        'actions' => ['install', 'update', 'privileges'],
        'key'     => 'CODE',
    ],
    'PRIVILEGE'                  => [
        'actions'      => ['install', 'update', 'privileges'],
        'key'          => ['CATEGORIE_ID', 'CODE'],
        'transformers' => [
            'CATEGORIE_ID' => 'SELECT id FROM categorie_privilege WHERE code = %s',
        ],
    ],
    'TYPE_INDICATEUR'            => [
        'actions' => ['install', 'update'],
        'key'     => 'ID',
    ],
    'INDICATEUR'                 => [
        'actions' => ['install', 'update'],
        'key'     => ['TYPE_INDICATEUR_ID', 'NUMERO'],
    ],
    'TYPE_HEURES'                => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
        'options' => ['update-ignore-cols' => ['ID', 'TYPE_HEURES_ELEMENT_ID']],
    ],
    'TYPE_INTERVENANT'           => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'PERIODE'                    => [
        'actions' => ['install'],
        'key'     => 'CODE',
        'options' => ['delete' => false],
    ],
    'TYPE_RESSOURCE'             => [
        'actions' => ['install'],
        'key'     => 'CODE',
    ],
    'DOSSIER_CHAMP_AUTRE_TYPE'   => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],

    /* Nomenclatures partiellement paramétrables (certaines colonnes) */
    'ANNEE'                      => [
        'actions' => ['install', 'update'],
        'key'     => 'ID',
        'options' => ['update-ignore-cols' => ['ACTIVE', 'TAUX_HETD']],
    ],
    'REGLE_STRUCTURE_VALIDATION' => [
        'actions' => ['install', 'update'],
        'key'     => ['TYPE_VOLUME_HORAIRE_ID', 'TYPE_INTERVENANT_ID'],
        'options' => ['update-ignore-cols' => ['PRIORITE']],
    ],
    'DOSSIER_CHAMP_AUTRE'        => [
        'actions' => ['install', 'update'],
        'key'     => 'ID',
        'options' => ['update-ignore-cols' => ['LIBELLE', 'DOSSIER_CHAMP_AUTRE_TYPE_ID', 'CONTENU', 'DESCRIPTION', 'OBLIGATOIRE']],
    ],


    /* Tables avec paramétrages pré-configurés (certaines colonnes + nouveaux enregistrements) */
    'WORKFLOW_ETAPE'             => [
        'actions'      => ['install', 'update', 'workflow-reset'],
        'key'          => ['CODE', 'ANNEE_ID'],
        'options'      => [
            'hard-delete'        => true,
            'update-ignore-cols' => [
                'LIBELLE_INTERVENANT', 'LIBELLE_AUTRES', 'DESC_NON_FRANCHIE', 'DESC_SANS_OBJECTIF', 'ORDRE',
            ],
        ],
        'transformers' => [
            'PERIMETRE_ID' => 'SELECT id FROM perimetre WHERE code = %s',
        ],
    ],
    'ADRESSE_NUMERO_COMPL'       => [
        'actions' => ['install'],
        'key'     => ['CODE'],
    ],
    'IMPORT_TABLES'              => [
        'actions' => ['install', 'update'],
        'key'     => 'TABLE_NAME',
        //'options' => ['update' => true, 'delete' => true],
        'options' => ['update-ignore-cols' => ['SYNC_FILTRE', 'SYNC_ENABLED', 'SYNC_JOB', 'SYNC_HOOK_BEFORE', 'SYNC_HOOK_AFTER']],
    ],
    'CC_ACTIVITE'                => [
        'actions' => ['install'],
        'key'     => 'CODE',
    ],
    'TYPE_INTERVENTION'          => [
        'actions' => ['install'],
        'key'     => 'CODE',
    ],
    'TAUX_REMU'                  => [
        'actions'      => ['install', 'update'],
        'options'      => [
            'update'      => true,
            'delete'      => false,
            'undelete'    => false,
            'soft-delete' => false,
        ],
        'key'          => 'CODE',
        'transformers' => [
            'TAUX_REMU_ID' => 'SELECT id FROM taux_remu WHERE histo_destruction IS NULL AND code = %s',
        ],
    ],
    'TAUX_REMU_VALEUR'           => [
        'actions'      => ['install', 'update'],
        'options'      => [
            'update'   => true,
            'delete'   => false,
            'undelete' => false,
        ],
        'key'          => ['TAUX_REMU_ID', 'DATE_EFFET'],
        'transformers' => [
            'TAUX_REMU_ID' => 'SELECT id FROM taux_remu WHERE histo_destruction IS NULL AND code = %s',
        ],
    ],
    'TYPE_MISSION'               => [
        'actions'      => ['install'],
        'key'          => ['CODE', 'ANNEE_ID'],
        'options'      => [
            'update' => false,
            'delete' => false,
        ],
        'transformers' => [
            'TAUX_REMU_ID'        => 'SELECT id FROM taux_remu WHERE histo_destruction IS NULL AND code = %s',
            'TAUX_REMU_MAJORE_ID' => 'SELECT id FROM taux_remu WHERE histo_destruction IS NULL AND code = %s',
        ],
    ],
    'SCENARIO'                   => [
        'actions' => ['install'],
        'key'     => 'LIBELLE',
        'options' => ['delete' => false],
    ],
    'ETAT_SORTIE'                => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
        'options' => ['update'           => true, 'delete' => false,
                      'update-cols'      => ['CSV_PARAMS', 'CSV_TRAITEMENT', 'PDF_TRAITEMENT'],
                      'update-only-null' => ['CSV_PARAMS', 'CSV_TRAITEMENT', 'PDF_TRAITEMENT'],
        ],
    ],
    'ROLE'                       => [
        'actions'      => ['install'],
        'key'          => 'CODE',
        'transformers' => [
            'PERIMETRE_ID' => 'SELECT id FROM perimetre WHERE code = %s',
        ],
    ],
    'ROLE_PRIVILEGE'             => [
        'actions'      => ['install'],
        'key'          => ['ROLE_ID', 'PRIVILEGE_ID'],
        'transformers' => [
            'ROLE_ID'      => 'SELECT id FROM role WHERE histo_destruction IS NULL AND code = %s',
            'PRIVILEGE_ID' => 'SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code || \'-\' || p.code = %s',
        ],
    ],
    'AFFECTATION'                => [
        'actions'      => ['install'],
        'key'          => ['UTILISATEUR_ID', 'ROLE_ID'],
        'transformers' => [
            'ROLE_ID'        => 'SELECT id FROM role WHERE histo_destruction IS NULL AND code = %s',
            'UTILISATEUR_ID' => 'SELECT id FROM utilisateur WHERE username = %s',
        ],
    ],
    'JOUR_FERIE'                 => [
        'actions' => ['install'],
        'key'     => ['DATE_JOUR'],
        'options' => ['update' => false, 'delete' => false],
    ],


    /* Jeu de données de configuration par défaut (tout perso) */
    'PAYS'                       => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'DEPARTEMENT'                => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'VOIRIE'                     => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'ETABLISSEMENT'              => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'CORPS'                      => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'GRADE'                      => [
        'actions'      => ['install'],
        'key'          => 'SOURCE_CODE',
        'transformers' => [
            'CORPS_ID' => 'SELECT id FROM corps WHERE source_code = %s',
        ],
    ],
    'DISCIPLINE'                 => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'DOMAINE_FONCTIONNEL'        => [
        'actions' => ['install'],
        'key'     => 'SOURCE_CODE',
    ],
    'FONCTION_REFERENTIEL'       => [
        'actions'      => ['install'],
        'key'          => 'CODE',
        'transformers' => [
            'DOMAINE_FONCTIONNEL_ID' => 'SELECT id FROM domaine_fonctionnel WHERE source_code = %s',
        ],
    ],
    'MOTIF_MODIFICATION_SERVICE' => [
        'actions' => ['install'],
        'key'     => 'CODE',
    ],
    'MOTIF_NON_PAIEMENT'         => [
        'actions' => ['install'],
        'key'     => 'CODE',
    ],
    'STATUT'                     => [
        'actions'      => ['install'],
        'key'          => ['CODE', 'ANNEE_ID'],
        'transformers' => [
            'TYPE_INTERVENANT_ID'    => 'SELECT id FROM type_intervenant WHERE code = %s',
            'CONTRAT_ETAT_SORTIE_ID' => 'SELECT id FROM etat_sortie WHERE code = %s',
            'AVENANT_ETAT_SORTIE_ID' => 'SELECT id FROM etat_sortie WHERE code = %s',
            'TAUX_REMU_ID'           => 'SELECT id FROM taux_remu WHERE code = %s',
        ],
    ],
    'TYPE_PIECE_JOINTE'          => [
        'actions' => ['install'],
        'key'     => 'CODE',
    ],
    'TYPE_PIECE_JOINTE_STATUT'   => [
        'actions'      => ['install'],
        'key'          => ['STATUT_ID', 'TYPE_PIECE_JOINTE_ID'],
        'transformers' => [
            'STATUT_ID'            => 'SELECT id FROM statut WHERE histo_destruction IS NULL AND code = %s',
            'TYPE_PIECE_JOINTE_ID' => 'SELECT id FROM type_piece_jointe WHERE histo_destruction IS NULL AND code = %s',
        ],
    ],
    'TYPE_SERVICE'               => [
        'actions' => ['install', 'update'],
        'key'     => 'CODE',
    ],
    'WORKFLOW_ETAPE_DEPENDANCE'  => [
        'actions'      => ['install', 'workflow-reset'],
        'key'          => ['ETAPE_SUIVANTE_ID', 'ETAPE_PRECEDANTE_ID', 'TYPE_INTERVENANT_ID'],
        'options'      => [
            'hard-delete' => true,
        ],
        'transformers' => [
            'ETAPE_PRECEDANTE_ID' => 'SELECT id FROM workflow_etape WHERE code || \'-\' || annee_id = %s',
            'ETAPE_SUIVANTE_ID'   => 'SELECT id FROM workflow_etape WHERE code || \'-\' || annee_id = %s',
            'TYPE_INTERVENANT_ID' => 'SELECT id FROM type_intervenant WHERE code = %s',
            'PERIMETRE_ID'        => 'SELECT id FROM perimetre WHERE code = %s',
        ],
    ],

    /* Paramètres par défaut, en fonction des nomenclatures ci-dessus */
    'PARAMETRE'                  => [
        'actions' => ['install', 'update'],
        'key'     => 'NOM',
        'options' => ['update-ignore-cols' => ['VALEUR']],
    ],


];