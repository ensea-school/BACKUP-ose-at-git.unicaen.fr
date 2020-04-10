<?php

//@formatter:off

return [
    'name'        => 'SCENARIO_NOEUD_SEUIL',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'SCENARIO_NOEUD_SEUIL_ID_SEQ',
    'columns'     => [
        'ASSIDUITE'            => [
            'name'        => 'ASSIDUITE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'DEDOUBLEMENT'         => [
            'name'        => 'DEDOUBLEMENT',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 2,
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
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'OUVERTURE'            => [
            'name'        => 'OUVERTURE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'SCENARIO_NOEUD_ID'    => [
            'name'        => 'SCENARIO_NOEUD_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'TYPE_INTERVENTION_ID' => [
            'name'        => 'TYPE_INTERVENTION_ID',
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
    ],
];

//@formatter:on
