<?php

//@formatter:off

return [
    'name'        => 'FORMULE_RESULTAT_SERVICE',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'FORMULE_RESULTAT_ID' => [
            'name'        => 'FORMULE_RESULTAT_ID',
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
        'HEURES_COMPL_FA'     => [
            'name'        => 'HEURES_COMPL_FA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'HEURES_COMPL_FC'     => [
            'name'        => 'HEURES_COMPL_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'HEURES_COMPL_FI'     => [
            'name'        => 'HEURES_COMPL_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'HEURES_PRIMES'       => [
            'name'        => 'HEURES_PRIMES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'ID'                  => [
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
        'NON_PAYABLE_FA'      => [
            'name'        => 'NON_PAYABLE_FA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'NON_PAYABLE_FC'      => [
            'name'        => 'NON_PAYABLE_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'NON_PAYABLE_FI'      => [
            'name'        => 'NON_PAYABLE_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'SERVICE_FA'          => [
            'name'        => 'SERVICE_FA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'SERVICE_FC'          => [
            'name'        => 'SERVICE_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'SERVICE_FI'          => [
            'name'        => 'SERVICE_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'SERVICE_ID'          => [
            'name'        => 'SERVICE_ID',
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
        'TOTAL'               => [
            'name'        => 'TOTAL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 11,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
