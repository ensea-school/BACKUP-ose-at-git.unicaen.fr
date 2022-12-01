<?php

//@formatter:off

return [
    'name'        => 'MISSION_TAUX_REMU_VALEUR',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'MISSION_TAUX_REMU_VALEU_ID_SEQ',
    'columns'     => [
        'DATE_EFFET'           => [
            'name'        => 'DATE_EFFET',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'ID'                   => [
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
        'MISSION_TAUX_REMU_ID' => [
            'name'        => 'MISSION_TAUX_REMU_ID',
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
        'VALEUR'               => [
            'name'        => 'VALEUR',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
