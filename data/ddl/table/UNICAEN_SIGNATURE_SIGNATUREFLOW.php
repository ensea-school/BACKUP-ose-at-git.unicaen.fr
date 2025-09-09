<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_SIGNATUREFLOW',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'UNICAEN_SIGNATURE_SIGNATUREFLOW_ID_SEQ',
    'columns'     => [
        'DESCRIPTION' => [
            'name'        => 'DESCRIPTION',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'ENABLED'     => [
            'name'        => 'ENABLED',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'ID'          => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '"SIGNATURE_SIGNATUREFLOW_ID_SEQ"."NEXTVAL"',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'LABEL'       => [
            'name'        => 'LABEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
