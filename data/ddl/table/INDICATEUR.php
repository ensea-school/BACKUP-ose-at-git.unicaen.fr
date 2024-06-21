<?php

//@formatter:off

return [
    'name'        => 'INDICATEUR',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'INDICATEUR_ID_SEQ',
    'columns'     => [
        'ENABLED'            => [
            'name'        => 'ENABLED',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 3,
            'commentaire' => 'Témoin indiquant si l\'indicateur est actif ou non',
        ],
        'ID'                 => [
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
        'IRRECEVABLES'       => [
            'name'        => 'IRRECEVABLES',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'LIBELLE_PLURIEL'    => [
            'name'        => 'LIBELLE_PLURIEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'LIBELLE_SINGULIER'  => [
            'name'        => 'LIBELLE_SINGULIER',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'NUMERO'             => [
            'name'        => 'NUMERO',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => 'Numero unique pérenne user-friendly',
        ],
        'ORDRE'              => [
            'name'        => 'ORDRE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '100',
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'ROUTE'              => [
            'name'        => 'ROUTE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 250,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'SPECIAL'            => [
            'name'        => 'SPECIAL',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'TYPE_INDICATEUR_ID' => [
            'name'        => 'TYPE_INDICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
