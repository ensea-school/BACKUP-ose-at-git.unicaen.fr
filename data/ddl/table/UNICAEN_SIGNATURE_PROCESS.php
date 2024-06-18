<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_PROCESS',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'UNICAEN_SIGNATURE_PROCESS_ID_SEQ',
    'columns'     => [
        'CURRENT_STEP'      => [
            'name'        => 'CURRENT_STEP',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'DATE_CREATED'      => [
            'name'        => 'DATE_CREATED',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => 'SYSDATE',
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'DOCUMENT_NAME'     => [
            'name'        => 'DOCUMENT_NAME',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'ID'                => [
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
        'LAST_UPDATE'       => [
            'name'        => 'LAST_UPDATE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => 'SYSDATE',
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'SIGNATURE_FLOW_ID' => [
            'name'        => 'SIGNATURE_FLOW_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'STATUS'            => [
            'name'        => 'STATUS',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
