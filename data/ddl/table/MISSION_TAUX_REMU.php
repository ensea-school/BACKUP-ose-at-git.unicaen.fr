<?php

//@formatter:off

return [
    'name'        => 'MISSION_TAUX_REMU',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'MISSION_TAUX_REMU_ID_SEQ',
    'columns'     => [
        'CODE'    => [
            'name'        => 'CODE',
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
        'ID'      => [
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
        'LIBELLE' => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
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
