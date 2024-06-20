<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_SIGNATUREFLOWSTEP',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'ALLRECIPIENTSSIGN'       => [
            'name'        => 'ALLRECIPIENTSSIGN',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => TRUE,
            'default'     => '1',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'EDITABLERECIPIENTS'      => [
            'name'        => 'EDITABLERECIPIENTS',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'ID'                      => [
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
        'LABEL'                   => [
            'name'        => 'LABEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 64,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'LETTERFILENAME'          => [
            'name'        => 'LETTERFILENAME',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 256,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'NOTIFICATIONSRECIPIENTS' => [
            'name'        => 'NOTIFICATIONSRECIPIENTS',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'OBSERVERSMETHOD'         => [
            'name'        => 'OBSERVERSMETHOD',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 64,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'OBSERVERS_OPTIONS'       => [
            'name'        => 'OBSERVERS_OPTIONS',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'OPTIONS'                 => [
            'name'        => 'OPTIONS',
            'type'        => 'clob',
            'bdd-type'    => 'CLOB',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'ORDERING'                => [
            'name'        => 'ORDERING',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'RECIPIENTSMETHOD'        => [
            'name'        => 'RECIPIENTSMETHOD',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 64,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'SIGNATUREFLOW_ID'        => [
            'name'        => 'SIGNATUREFLOW_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'SIGNLEVEL'               => [
            'name'        => 'SIGNLEVEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 256,
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
