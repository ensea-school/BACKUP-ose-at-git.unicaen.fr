<?php

//@formatter:off

return [
    'name'        => 'TMP_SCENARIO_NOEUD_EFFECTIF',
    'temporary'   => TRUE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'EFFECTIF'          => [
            'name'        => 'EFFECTIF',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'ETAPE_ID'          => [
            'name'        => 'ETAPE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'SCENARIO_NOEUD_ID' => [
            'name'        => 'SCENARIO_NOEUD_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'TYPE_HEURES_ID'    => [
            'name'        => 'TYPE_HEURES_ID',
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
