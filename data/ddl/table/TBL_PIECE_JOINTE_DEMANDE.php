<?php

//@formatter:off

return [
    'name'        => 'TBL_PIECE_JOINTE_DEMANDE',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Pièces justificatives (demandes)',
    'sequence'    => 'TBL_PIECE_JOINTE_DEMAND_ID_SEQ',
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
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'CODE_INTERVENANT'     => [
            'name'        => 'CODE_INTERVENANT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
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
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'ID'                   => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
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
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
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
            'position'    => 8,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
