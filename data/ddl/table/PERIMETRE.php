<?php

//@formatter:off

return [
    'name'        => 'PERIMETRE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'PERIMETRE_ID_SEQ',
    'columns'     => [
        'CODE'    => [
            'name'        => 'CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'ID'      => [
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
        'LIBELLE' => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 150,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
