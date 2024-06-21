<?php

//@formatter:off

return [
    'name'        => 'ETAT_SORTIE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'ETAT_SORTIE_ID_SEQ',
    'columns'     => [
        'AUTO_BREAK'     => [
            'name'        => 'AUTO_BREAK',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'BLOC10_NOM'     => [
            'name'        => 'BLOC10_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 38,
            'commentaire' => NULL,
        ],
        'BLOC10_REQUETE' => [
            'name'        => 'BLOC10_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 40,
            'commentaire' => NULL,
        ],
        'BLOC10_ZONE'    => [
            'name'        => 'BLOC10_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 39,
            'commentaire' => NULL,
        ],
        'BLOC1_NOM'      => [
            'name'        => 'BLOC1_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'BLOC1_REQUETE'  => [
            'name'        => 'BLOC1_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'BLOC1_ZONE'     => [
            'name'        => 'BLOC1_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'BLOC2_NOM'      => [
            'name'        => 'BLOC2_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'BLOC2_REQUETE'  => [
            'name'        => 'BLOC2_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'BLOC2_ZONE'     => [
            'name'        => 'BLOC2_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'BLOC3_NOM'      => [
            'name'        => 'BLOC3_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'BLOC3_REQUETE'  => [
            'name'        => 'BLOC3_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'BLOC3_ZONE'     => [
            'name'        => 'BLOC3_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'BLOC4_NOM'      => [
            'name'        => 'BLOC4_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'BLOC4_REQUETE'  => [
            'name'        => 'BLOC4_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'BLOC4_ZONE'     => [
            'name'        => 'BLOC4_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'BLOC5_NOM'      => [
            'name'        => 'BLOC5_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'BLOC5_REQUETE'  => [
            'name'        => 'BLOC5_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'BLOC5_ZONE'     => [
            'name'        => 'BLOC5_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'BLOC6_NOM'      => [
            'name'        => 'BLOC6_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'BLOC6_REQUETE'  => [
            'name'        => 'BLOC6_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'BLOC6_ZONE'     => [
            'name'        => 'BLOC6_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'BLOC7_NOM'      => [
            'name'        => 'BLOC7_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'BLOC7_REQUETE'  => [
            'name'        => 'BLOC7_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'BLOC7_ZONE'     => [
            'name'        => 'BLOC7_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'BLOC8_NOM'      => [
            'name'        => 'BLOC8_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'BLOC8_REQUETE'  => [
            'name'        => 'BLOC8_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 34,
            'commentaire' => NULL,
        ],
        'BLOC8_ZONE'     => [
            'name'        => 'BLOC8_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'BLOC9_NOM'      => [
            'name'        => 'BLOC9_NOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 35,
            'commentaire' => NULL,
        ],
        'BLOC9_REQUETE'  => [
            'name'        => 'BLOC9_REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 37,
            'commentaire' => NULL,
        ],
        'BLOC9_ZONE'     => [
            'name'        => 'BLOC9_ZONE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 36,
            'commentaire' => NULL,
        ],
        'CLE'            => [
            'name'        => 'CLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'CODE'           => [
            'name'        => 'CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 150,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'CSV_PARAMS'     => [
            'name'        => 'CSV_PARAMS',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'CSV_TRAITEMENT' => [
            'name'        => 'CSV_TRAITEMENT',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'FICHIER'        => [
            'name'        => 'FICHIER',
            'type'        => 'blob',
            'bdd-type'    => 'BLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'ID'             => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'LIBELLE'        => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 250,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'PDF_TRAITEMENT' => [
            'name'        => 'PDF_TRAITEMENT',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'REQUETE'        => [
            'name'        => 'REQUETE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
