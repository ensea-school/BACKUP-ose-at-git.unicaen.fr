<?php

//@formatter:off

return [
    'name'        => 'ADRESSE_INTERVENANT',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'ADRESSE_INTERVENANT_ID_SEQ',
    'columns'     => [
        'BATIMENT'               => [
            'name'        => 'BATIMENT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'CODE_POSTAL'            => [
            'name'        => 'CODE_POSTAL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 15,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'HISTO_CREATEUR_ID'      => [
            'name'        => 'HISTO_CREATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'HISTO_CREATION'         => [
            'name'        => 'HISTO_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTEUR_ID'   => [
            'name'        => 'HISTO_DESTRUCTEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTION'      => [
            'name'        => 'HISTO_DESTRUCTION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATEUR_ID'  => [
            'name'        => 'HISTO_MODIFICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATION'     => [
            'name'        => 'HISTO_MODIFICATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'commentaire' => NULL,
        ],
        'ID'                     => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'         => [
            'name'        => 'INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'LOCALITE'               => [
            'name'        => 'LOCALITE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 120,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'MENTION_COMPLEMENTAIRE' => [
            'name'        => 'MENTION_COMPLEMENTAIRE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'NOM_VOIE'               => [
            'name'        => 'NOM_VOIE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 120,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'NO_VOIE'                => [
            'name'        => 'NO_VOIE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'PAYS_CODE_INSEE'        => [
            'name'        => 'PAYS_CODE_INSEE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 3,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'PAYS_LIBELLE'           => [
            'name'        => 'PAYS_LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'SOURCE_CODE'            => [
            'name'        => 'SOURCE_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'SOURCE_ID'              => [
            'name'        => 'SOURCE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'TEL_DOMICILE'           => [
            'name'        => 'TEL_DOMICILE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 25,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'VILLE'                  => [
            'name'        => 'VILLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 120,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
