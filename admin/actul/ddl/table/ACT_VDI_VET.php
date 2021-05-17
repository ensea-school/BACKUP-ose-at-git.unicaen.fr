<?php

//@formatter:off

return [
    'name'        => 'ACT_VDI_VET',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => 'ACT_VDI_VET',
    'sequence'    => NULL,
    'columns'     => [
        'ANNEE_ID' => [
            'name'        => 'ANNEE_ID',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'COD_VDI'  => [
            'name'        => 'COD_VDI',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'COD_VET'  => [
            'name'        => 'COD_VET',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
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
