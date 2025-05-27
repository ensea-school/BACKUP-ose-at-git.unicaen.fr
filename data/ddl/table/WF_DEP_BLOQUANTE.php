<?php

//@formatter:off

return [
    'name'        => 'WF_DEP_BLOQUANTE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'ID'              => [
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
        'TBL_WORKFLOW_ID' => [
            'name'        => 'TBL_WORKFLOW_ID',
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
        'WF_ETAPE_DEP_ID' => [
            'name'        => 'WF_ETAPE_DEP_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
