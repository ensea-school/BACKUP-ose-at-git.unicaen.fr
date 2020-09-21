<?php

//@formatter:off

return [
    'name'        => 'DOSSIER_CHAMP_AUTRE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'CONTENU'                     => [
            'name'        => 'CONTENU',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'DESCRIPTION'                 => [
            'name'        => 'DESCRIPTION',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 3000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'DOSSIER_CHAMP_AUTRE_TYPE_ID' => [
            'name'        => 'DOSSIER_CHAMP_AUTRE_TYPE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'ID'                          => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'LIBELLE'                     => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 200,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'OBLIGATOIRE'                 => [
            'name'        => 'OBLIGATOIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'SQL_VALUE'        => [
            'name'        => 'SQL_VALUE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'JSON_VALUE'        => [
            'name'        => 'JSON_VALUE',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 39,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
