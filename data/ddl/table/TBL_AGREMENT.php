<?php

//@formatter:off

return [
    'name'        => 'TBL_AGREMENT',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Gestion des agréments',
    'sequence'    => 'TBL_AGREMENT_ID_SEQ',
    'columns'     => [
        'AGREMENT_ID'      => [
            'name'        => 'AGREMENT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => 'ID de l\'agrément si agréé',
        ],
        'ANNEE_AGREMENT'   => [
            'name'        => 'ANNEE_AGREMENT',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'ANNEE_ID'         => [
            'name'        => 'ANNEE_ID',
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
        'CODE_INTERVENANT' => [
            'name'        => 'CODE_INTERVENANT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'DUREE_VIE'        => [
            'name'        => 'DUREE_VIE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'ID'               => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'   => [
            'name'        => 'INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'OBLIGATOIRE'      => [
            'name'        => 'OBLIGATOIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => 'Témoin (1 ou 0) pour définir si l\'agrément doit être demandé obligatoirement ou non',
        ],
        'STRUCTURE_ID'     => [
            'name'        => 'STRUCTURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'TYPE_AGREMENT_ID' => [
            'name'        => 'TYPE_AGREMENT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
