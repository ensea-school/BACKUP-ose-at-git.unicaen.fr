<?php

//@formatter:off

return [
    'name'        => 'PEG_TYPE_FORMATION',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'LIBELLE_COURT' => [
            'name'        => 'LIBELLE_COURT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'LIBELLE_LONG'  => [
            'name'        => 'LIBELLE_LONG',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 200,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'SOURCE_CODE'   => [
            'name'        => 'SOURCE_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
