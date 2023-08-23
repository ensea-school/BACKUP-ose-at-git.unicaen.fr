<?php

//@formatter:off

return [
    'name'        => 'TBL_PIECE_JOINTE',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Pièces justificatives',
    'sequence'    => 'TBL_PIECE_JOINTE_ID_SEQ',
    'columns'     => [
        'ANNEE_ID'             => [
            'name'        => 'ANNEE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'DEMANDEE'             => [
            'name'        => 'DEMANDEE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 6,
            'commentaire' => 'Témoin (1 si la PJ est demandée)',
        ],
        'FOURNIE'              => [
            'name'        => 'FOURNIE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 7,
            'commentaire' => 'Témoin (1 si la PJ est fournie)',
        ],
        'HEURES_POUR_SEUIL'    => [
            'name'        => 'HEURES_POUR_SEUIL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => 'NB d\'heures de seuil pour la demande',
        ],
        'ID'                   => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '"TBL_PIECE_JOINTE_ID_SEQ"."NEXTVAL"',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'       => [
            'name'        => 'INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'OBLIGATOIRE'          => [
            'name'        => 'OBLIGATOIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'PIECE_JOINTE_ID'      => [
            'name'        => 'PIECE_JOINTE_ID',
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
        'TYPE_PIECE_JOINTE_ID' => [
            'name'        => 'TYPE_PIECE_JOINTE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'VALIDEE'              => [
            'name'        => 'VALIDEE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 8,
            'commentaire' => 'Témoin (1 si la PJ est validée)',
        ],
    ],
];

//@formatter:on
