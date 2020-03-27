<?php

//@formatter:off

return [
    'name'        => 'VALIDATION_VOL_HORAIRE_REF',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'VALIDATION_VOL_HORAIRE__ID_SEQ',
    'columns'     => [
        'VALIDATION_ID'         => [
            'name'        => 'VALIDATION_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'VOLUME_HORAIRE_REF_ID' => [
            'name'        => 'VOLUME_HORAIRE_REF_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
