<?php

//@formatter:off

return [
    'name'        => 'ACT_VHENS_HEURES',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'HEURES'                   => [
            'name'        => 'HEURES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'Z_ELEMENT_PEDAGOGIQUE_ID' => [
            'name'        => 'Z_ELEMENT_PEDAGOGIQUE_ID',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'Z_SOURCE_ID'              => [
            'name'        => 'Z_SOURCE_ID',
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
        'Z_TYPE_INTERVENTION_ID'   => [
            'name'        => 'Z_TYPE_INTERVENTION_ID',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
