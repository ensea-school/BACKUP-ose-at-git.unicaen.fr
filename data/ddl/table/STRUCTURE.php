<?php

//@formatter:off

return [
    'name'        => 'STRUCTURE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'STRUCTURE_ID_SEQ',
    'columns'     => [
        'ADRESSE_CODE_POSTAL'     => [
            'name'        => 'ADRESSE_CODE_POSTAL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 15,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'ADRESSE_COMMUNE'         => [
            'name'        => 'ADRESSE_COMMUNE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'ADRESSE_LIEU_DIT'        => [
            'name'        => 'ADRESSE_LIEU_DIT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'ADRESSE_NUMERO'          => [
            'name'        => 'ADRESSE_NUMERO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'ADRESSE_NUMERO_COMPL_ID' => [
            'name'        => 'ADRESSE_NUMERO_COMPL_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'ADRESSE_PAYS_ID'         => [
            'name'        => 'ADRESSE_PAYS_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'ADRESSE_PRECISIONS'      => [
            'name'        => 'ADRESSE_PRECISIONS',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 240,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'ADRESSE_VOIE'            => [
            'name'        => 'ADRESSE_VOIE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'ADRESSE_VOIRIE_ID'       => [
            'name'        => 'ADRESSE_VOIRIE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'AFF_ADRESSE_CONTRAT'     => [
            'name'        => 'AFF_ADRESSE_CONTRAT',
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
        'AUTRE_1'                 => [
            'name'        => 'AUTRE_1',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'AUTRE_2'                 => [
            'name'        => 'AUTRE_2',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'AUTRE_3'                 => [
            'name'        => 'AUTRE_3',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'AUTRE_4'                 => [
            'name'        => 'AUTRE_4',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'AUTRE_5'                 => [
            'name'        => 'AUTRE_5',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'CENTRE_COUT_ID'          => [
            'name'        => 'CENTRE_COUT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'CODE'                    => [
            'name'        => 'CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'DOMAINE_FONCTIONNEL_ID'  => [
            'name'        => 'DOMAINE_FONCTIONNEL_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 4,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'ENSEIGNEMENT'            => [
            'name'        => 'ENSEIGNEMENT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'HISTO_CREATEUR_ID'       => [
            'name'        => 'HISTO_CREATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'HISTO_CREATION'          => [
            'name'        => 'HISTO_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTEUR_ID'    => [
            'name'        => 'HISTO_DESTRUCTEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTION'       => [
            'name'        => 'HISTO_DESTRUCTION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATEUR_ID'   => [
            'name'        => 'HISTO_MODIFICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATION'      => [
            'name'        => 'HISTO_MODIFICATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'ID'                      => [
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
        'IDS'                     => [
            'name'        => 'IDS',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'LIBELLES_COURTS'         => [
            'name'        => 'LIBELLES_COURTS',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'LIBELLE_COURT'           => [
            'name'        => 'LIBELLE_COURT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 25,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'LIBELLE_LONG'            => [
            'name'        => 'LIBELLE_LONG',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 200,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'SOURCE_CODE'             => [
            'name'        => 'SOURCE_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'SOURCE_ID'               => [
            'name'        => 'SOURCE_ID',
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
        'STRUCTURE_ID'            => [
            'name'        => 'STRUCTURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => 'structure par
ente',
        ],
    ],
];

//@formatter:on
