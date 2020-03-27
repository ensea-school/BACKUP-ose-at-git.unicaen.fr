<?php

//@formatter:off

return [
    'name'        => 'SYNC_LOG',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'SYNC_LOG_ID_SEQ',
    'columns'     => [
        'DATE_SYNC'   => [
            'name'        => 'DATE_SYNC',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'ID'          => [
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
        'MESSAGE'     => [
            'name'        => 'MESSAGE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'SOURCE_CODE' => [
            'name'        => 'SOURCE_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 200,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'TABLE_NAME'  => [
            'name'        => 'TABLE_NAME',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
