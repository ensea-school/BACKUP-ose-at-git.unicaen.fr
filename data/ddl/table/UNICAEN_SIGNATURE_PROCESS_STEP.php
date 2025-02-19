<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_PROCESS_STEP',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'UNICAEN_SIGNATURE_PROCESS_STEP_ID_SEQ',
    'columns'     => [
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
        'LABEL'        => [
            'name'        => 'LABEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 2000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'PROCESS_ID'   => [
            'name'        => 'PROCESS_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
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
            'position'    => 3,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
