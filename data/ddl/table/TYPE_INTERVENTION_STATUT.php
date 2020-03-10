<?php

//@formatter:off

return [
    'name'        => 'TYPE_INTERVENTION_STATUT',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'TYPE_INTERVENTION_STATU_ID_SEQ',
    'columns'     => [
        'ID'                       => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'STATUT_INTERVENANT_ID'    => [
            'name'        => 'STATUT_INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'TAUX_HETD_COMPLEMENTAIRE' => [
            'name'        => 'TAUX_HETD_COMPLEMENTAIRE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'TAUX_HETD_SERVICE'        => [
            'name'        => 'TAUX_HETD_SERVICE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'TYPE_INTERVENTION_ID'     => [
            'name'        => 'TYPE_INTERVENTION_ID',
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
