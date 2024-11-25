<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_SIGNATURE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'UNICAEN_SIGNATURE_SIGNATURE_ID_SEQ',
    'columns'     => [
        'ALLSIGNTOCOMPLETE'       => [
            'name'        => 'ALLSIGNTOCOMPLETE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'CONTEXT_LONG'            => [
            'name'        => 'CONTEXT_LONG',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 2000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'CONTEXT_SHORT'           => [
            'name'        => 'CONTEXT_SHORT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 2000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'DATECREATED'             => [
            'name'        => 'DATECREATED',
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
        'DATESEND'                => [
            'name'        => 'DATESEND',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => 'SYSDATE',
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'DATEUPDATE'              => [
            'name'        => 'DATEUPDATE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => 'SYSDATE',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'DESCRIPTION'             => [
            'name'        => 'DESCRIPTION',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'DOCUMENT_LOCALKEY'       => [
            'name'        => 'DOCUMENT_LOCALKEY',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'DOCUMENT_PATH'           => [
            'name'        => 'DOCUMENT_PATH',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'DOCUMENT_REMOTEKEY'      => [
            'name'        => 'DOCUMENT_REMOTEKEY',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'ID'                      => [
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
        'LABEL'                   => [
            'name'        => 'LABEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'LETTERFILE_KEY'          => [
            'name'        => 'LETTERFILE_KEY',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'LETTERFILE_PROCESS'      => [
            'name'        => 'LETTERFILE_PROCESS',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'LETTERFILE_URL'          => [
            'name'        => 'LETTERFILE_URL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'NOTIFICATIONDESCRIPTION' => [
            'name'        => 'NOTIFICATIONDESCRIPTION',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'NOTIFICATIONSRECIPIENTS' => [
            'name'        => 'NOTIFICATIONSRECIPIENTS',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 38,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
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
            'default'     => '0',
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'REFUSED_TEXT'            => [
            'name'        => 'REFUSED_TEXT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 400,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'STATUS'                  => [
            'name'        => 'STATUS',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '101',
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'TYPE'                    => [
            'name'        => 'TYPE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 32,
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
