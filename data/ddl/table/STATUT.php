<?php

//@formatter:off

return [
    'name'        => 'STATUT',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'STATUT_ID_SEQ',
    'columns'     => [
        'ANNEE_ID'                       => [
            'name'        => 'ANNEE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'AVENANT_ETAT_SORTIE_ID'         => [
            'name'        => 'AVENANT_ETAT_SORTIE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 92,
            'commentaire' => NULL,
        ],
        'CLOTURE'                        => [
            'name'        => 'CLOTURE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 61,
            'commentaire' => NULL,
        ],
        'CODE'                           => [
            'name'        => 'CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_1'                => [
            'name'        => 'CODES_CORRESP_1',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 67,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_2'                => [
            'name'        => 'CODES_CORRESP_2',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 68,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_3'                => [
            'name'        => 'CODES_CORRESP_3',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 69,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_4'                => [
            'name'        => 'CODES_CORRESP_4',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 70,
            'commentaire' => NULL,
        ],
        'CONSEIL_ACA'                    => [
            'name'        => 'CONSEIL_ACA',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 45,
            'commentaire' => NULL,
        ],
        'CONSEIL_ACA_DUREE_VIE'          => [
            'name'        => 'CONSEIL_ACA_DUREE_VIE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '5',
            'position'    => 47,
            'commentaire' => NULL,
        ],
        'CONSEIL_ACA_VISUALISATION'      => [
            'name'        => 'CONSEIL_ACA_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 46,
            'commentaire' => NULL,
        ],
        'CONSEIL_RESTREINT'              => [
            'name'        => 'CONSEIL_RESTREINT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 42,
            'commentaire' => NULL,
        ],
        'CONSEIL_RESTREINT_DUREE_VIE'    => [
            'name'        => 'CONSEIL_RESTREINT_DUREE_VIE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 44,
            'commentaire' => NULL,
        ],
        'CONSEIL_RESTREINT_VISU'         => [
            'name'        => 'CONSEIL_RESTREINT_VISU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 43,
            'commentaire' => NULL,
        ],
        'CONTRAT'                        => [
            'name'        => 'CONTRAT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 48,
            'commentaire' => NULL,
        ],
        'CONTRAT_DEPOT'                  => [
            'name'        => 'CONTRAT_DEPOT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 50,
            'commentaire' => NULL,
        ],
        'CONTRAT_ETAT_SORTIE_ID'         => [
            'name'        => 'CONTRAT_ETAT_SORTIE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 80,
            'commentaire' => NULL,
        ],
        'CONTRAT_GENERATION'             => [
            'name'        => 'CONTRAT_GENERATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 82,
            'commentaire' => NULL,
        ],
        'CONTRAT_VISUALISATION'          => [
            'name'        => 'CONTRAT_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 49,
            'commentaire' => NULL,
        ],
        'DEPASSEMENT_SERVICE_DU_SANS_HC' => [
            'name'        => 'DEPASSEMENT_SERVICE_DU_SANS_HC',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'DOSSIER'                        => [
            'name'        => 'DOSSIER',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'DOSSIER_ADRESSE'                => [
            'name'        => 'DOSSIER_ADRESSE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_1'                => [
            'name'        => 'DOSSIER_AUTRE_1',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_1_EDITION'        => [
            'name'        => 'DOSSIER_AUTRE_1_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_1_VISUALISATION'  => [
            'name'        => 'DOSSIER_AUTRE_1_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_2'                => [
            'name'        => 'DOSSIER_AUTRE_2',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_2_EDITION'        => [
            'name'        => 'DOSSIER_AUTRE_2_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_2_VISUALISATION'  => [
            'name'        => 'DOSSIER_AUTRE_2_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_3'                => [
            'name'        => 'DOSSIER_AUTRE_3',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_3_EDITION'        => [
            'name'        => 'DOSSIER_AUTRE_3_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_3_VISUALISATION'  => [
            'name'        => 'DOSSIER_AUTRE_3_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_4'                => [
            'name'        => 'DOSSIER_AUTRE_4',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_4_EDITION'        => [
            'name'        => 'DOSSIER_AUTRE_4_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 34,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_4_VISUALISATION'  => [
            'name'        => 'DOSSIER_AUTRE_4_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_5'                => [
            'name'        => 'DOSSIER_AUTRE_5',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 35,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_5_EDITION'        => [
            'name'        => 'DOSSIER_AUTRE_5_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 37,
            'commentaire' => NULL,
        ],
        'DOSSIER_AUTRE_5_VISUALISATION'  => [
            'name'        => 'DOSSIER_AUTRE_5_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 36,
            'commentaire' => NULL,
        ],
        'DOSSIER_BANQUE'                 => [
            'name'        => 'DOSSIER_BANQUE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'DOSSIER_CONTACT'                => [
            'name'        => 'DOSSIER_CONTACT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'DOSSIER_EDITION'                => [
            'name'        => 'DOSSIER_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'DOSSIER_EMAIL_PERSO'            => [
            'name'        => 'DOSSIER_EMAIL_PERSO',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'DOSSIER_EMPLOYEUR'              => [
            'name'        => 'DOSSIER_EMPLOYEUR',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'DOSSIER_IDENTITE_COMP'          => [
            'name'        => 'DOSSIER_IDENTITE_COMP',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'DOSSIER_INSEE'                  => [
            'name'        => 'DOSSIER_INSEE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'DOSSIER_SELECTIONNABLE'         => [
            'name'        => 'DOSSIER_SELECTIONNABLE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'DOSSIER_STATUT'                 => [
            'name'        => 'DOSSIER_STATUT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 86,
            'commentaire' => NULL,
        ],
        'DOSSIER_TEL_PERSO'              => [
            'name'        => 'DOSSIER_TEL_PERSO',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'DOSSIER_VISUALISATION'          => [
            'name'        => 'DOSSIER_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'FORMULE_VISUALISATION'          => [
            'name'        => 'FORMULE_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 66,
            'commentaire' => NULL,
        ],
        'HISTO_CREATEUR_ID'              => [
            'name'        => 'HISTO_CREATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 72,
            'commentaire' => NULL,
        ],
        'HISTO_CREATION'                 => [
            'name'        => 'HISTO_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 71,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTEUR_ID'           => [
            'name'        => 'HISTO_DESTRUCTEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 76,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTION'              => [
            'name'        => 'HISTO_DESTRUCTION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 75,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATEUR_ID'          => [
            'name'        => 'HISTO_MODIFICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 74,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATION'             => [
            'name'        => 'HISTO_MODIFICATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 73,
            'commentaire' => NULL,
        ],
        'ID'                             => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'LIBELLE'                        => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 128,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'MISSION'                        => [
            'name'        => 'MISSION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 83,
            'commentaire' => NULL,
        ],
        'MISSION_EDITION'                => [
            'name'        => 'MISSION_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 89,
            'commentaire' => NULL,
        ],
        'MISSION_REALISE_EDITION'        => [
            'name'        => 'MISSION_REALISE_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 84,
            'commentaire' => NULL,
        ],
        'MISSION_VISUALISATION'          => [
            'name'        => 'MISSION_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 88,
            'commentaire' => NULL,
        ],
        'MODE_ENSEIGNEMENT_PREVISIONNEL'      => [
            'name'        => 'MODE_ENSEIGNEMENT_PREVISIONNEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 94,
            'commentaire' => NULL,
        ],
        'MODE_ENSEIGNEMENT_REALISE'           => [
            'name'        => 'MODE_ENSEIGNEMENT_REALISE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 95,
            'commentaire' => NULL,
        ],
        'MODIF_SERVICE_DU'               => [
            'name'        => 'MODIF_SERVICE_DU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 62,
            'commentaire' => NULL,
        ],
        'MODIF_SERVICE_DU_VISUALISATION' => [
            'name'        => 'MODIF_SERVICE_DU_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 63,
            'commentaire' => NULL,
        ],
        'MOTIF_NON_PAIEMENT'             => [
            'name'        => 'MOTIF_NON_PAIEMENT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 65,
            'commentaire' => NULL,
        ],
        'OFFRE_EMPLOI_POSTULER'          => [
            'name'        => 'OFFRE_EMPLOI_POSTULER',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 87,
            'commentaire' => NULL,
        ],
        'ORDRE'                          => [
            'name'        => 'ORDRE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '9999',
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'PAIEMENT'                       => [
            'name'        => 'PAIEMENT',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 93,
            'commentaire' => NULL,
        ],
        'PAIEMENT_VISUALISATION'         => [
            'name'        => 'PAIEMENT_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 64,
            'commentaire' => NULL,
        ],
        'PJ_ARCHIVAGE'                   => [
            'name'        => 'PJ_ARCHIVAGE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 41,
            'commentaire' => NULL,
        ],
        'PJ_EDITION'                     => [
            'name'        => 'PJ_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 40,
            'commentaire' => NULL,
        ],
        'PJ_TELECHARGEMENT'              => [
            'name'        => 'PJ_TELECHARGEMENT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 39,
            'commentaire' => NULL,
        ],
        'PJ_VISUALISATION'               => [
            'name'        => 'PJ_VISUALISATION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 38,
            'commentaire' => NULL,
        ],
        'PRIORITAIRE_INDICATEURS'        => [
            'name'        => 'PRIORITAIRE_INDICATEURS',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'REFERENTIEL_PREVU'              => [
            'name'        => 'REFERENTIEL_PREVU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 58,
            'commentaire' => NULL,
        ],
        'REFERENTIEL_PREVU_EDITION'      => [
            'name'        => 'REFERENTIEL_PREVU_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 60,
            'commentaire' => NULL,
        ],
        'REFERENTIEL_PREVU_VISU'         => [
            'name'        => 'REFERENTIEL_PREVU_VISU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 59,
            'commentaire' => NULL,
        ],
        'REFERENTIEL_REALISE'            => [
            'name'        => 'REFERENTIEL_REALISE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 77,
            'commentaire' => NULL,
        ],
        'REFERENTIEL_REALISE_EDITION'    => [
            'name'        => 'REFERENTIEL_REALISE_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 78,
            'commentaire' => NULL,
        ],
        'REFERENTIEL_REALISE_VISU'       => [
            'name'        => 'REFERENTIEL_REALISE_VISU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 79,
            'commentaire' => NULL,
        ],
        'SERVICE_EXTERIEUR'              => [
            'name'        => 'SERVICE_EXTERIEUR',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 57,
            'commentaire' => NULL,
        ],
        'SERVICE_PREVU'                  => [
            'name'        => 'SERVICE_PREVU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 51,
            'commentaire' => NULL,
        ],
        'SERVICE_PREVU_EDITION'          => [
            'name'        => 'SERVICE_PREVU_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 53,
            'commentaire' => NULL,
        ],
        'SERVICE_PREVU_VISU'             => [
            'name'        => 'SERVICE_PREVU_VISU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 52,
            'commentaire' => NULL,
        ],
        'SERVICE_REALISE'                => [
            'name'        => 'SERVICE_REALISE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 54,
            'commentaire' => NULL,
        ],
        'SERVICE_REALISE_EDITION'        => [
            'name'        => 'SERVICE_REALISE_EDITION',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 56,
            'commentaire' => NULL,
        ],
        'SERVICE_REALISE_VISU'           => [
            'name'        => 'SERVICE_REALISE_VISU',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 55,
            'commentaire' => NULL,
        ],
        'SERVICE_STATUTAIRE'             => [
            'name'        => 'SERVICE_STATUTAIRE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'TAUX_CHARGES_PATRONALES'        => [
            'name'        => 'TAUX_CHARGES_PATRONALES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'TAUX_CHARGES_TTC'               => [
            'name'        => 'TAUX_CHARGES_TTC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 81,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_ID'                   => [
            'name'        => 'TAUX_REMU_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 85,
            'commentaire' => NULL,
        ],
        'TYPE_INTERVENANT_ID'            => [
            'name'        => 'TYPE_INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
