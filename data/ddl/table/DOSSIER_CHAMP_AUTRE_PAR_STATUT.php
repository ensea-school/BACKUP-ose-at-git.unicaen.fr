<?php

//@formatter:off

return [
    'name'        => 'DOSSIER_CHAMP_AUTRE_PAR_STATUT',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'DOSSIER_CHAMP_AUTRE_ID' => [
            'name'        => 'DOSSIER_CHAMP_AUTRE_ID',
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
        'STATUT_ID'              => [
            'name'        => 'STATUT_ID',
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
    ],
];

//@formatter:on
