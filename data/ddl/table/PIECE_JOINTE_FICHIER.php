<?php

//@formatter:off

return [
    'name'        => 'PIECE_JOINTE_FICHIER',
    'temporary'   => false,
    'logging'     => true,
    'commentaire' => null,
    'sequence'    => 'PIECE_JOINTE_FICHIER_ID_SEQ',
    'columns'     => [
        'FICHIER_ID'      => [
            'name'        => 'FICHIER_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => null,
            'precision'   => null,
            'nullable'    => false,
            'default'     => null,
            'position'    => 2,
            'commentaire' => null,
        ],
        'PIECE_JOINTE_ID' => [
            'name'        => 'PIECE_JOINTE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => null,
            'precision'   => null,
            'nullable'    => false,
            'default'     => null,
            'position'    => 1,
            'commentaire' => null,
        ],
        'VALIDATION_ID'         => [
            'name'        => 'VALIDATION_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => null,
            'precision'   => null,
            'nullable'    => true,
            'default'     => null,
            'position'    => 9,
            'commentaire' => null,
        ],
    ],
];

//@formatter:on
