<?php

//@formatter:off

return [
    'name'        => 'PIECE_JOINTE_FICHIER',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'PIECE_JOINTE_FICHIER_ID_SEQ',
    'columns'     => [
        'FICHIER_ID'      => [
            'name'        => 'FICHIER_ID',
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
        'PIECE_JOINTE_ID' => [
            'name'        => 'PIECE_JOINTE_ID',
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
    ],
];

//@formatter:on
