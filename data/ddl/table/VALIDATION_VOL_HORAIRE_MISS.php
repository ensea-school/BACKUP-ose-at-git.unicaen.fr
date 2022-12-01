<?php

//@formatter:off

return [
    'name'        => 'VALIDATION_VOL_HORAIRE_MISS',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'VALIDATION_VOL_HORAIRE__ID_SEQ',
    'columns'     => [
        'VALIDATION_ID'             => [
            'name'        => 'VALIDATION_ID',
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
        'VOLUME_HORAIRE_MISSION_ID' => [
            'name'        => 'VOLUME_HORAIRE_MISSION_ID',
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
