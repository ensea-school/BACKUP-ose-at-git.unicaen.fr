<?php

//@formatter:off

return [
    'name'        => 'ACT_LIEN',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'CHOIX_MAXIMUM'  => [
            'name'        => 'CHOIX_MAXIMUM',
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
        'CHOIX_MINIMUM'  => [
            'name'        => 'CHOIX_MINIMUM',
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
        'Z_NOEUD_INF_ID' => [
            'name'        => 'Z_NOEUD_INF_ID',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'Z_NOEUD_SUP_ID' => [
            'name'        => 'Z_NOEUD_SUP_ID',
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
