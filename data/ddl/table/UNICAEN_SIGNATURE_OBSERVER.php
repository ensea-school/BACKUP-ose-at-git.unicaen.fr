<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_OBSERVER',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'UNICAEN_SIGNATURE_OBSERVER_ID_SEQ',
    'columns'     => [
        'EMAIL'        => [
            'name'        => 'EMAIL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'FIRSTNAME'    => [
            'name'        => 'FIRSTNAME',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'ID'           => [
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
        'LASTNAME'     => [
            'name'        => 'LASTNAME',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'SIGNATURE_ID' => [
            'name'        => 'SIGNATURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
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
