<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_SAISIE',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Service (pour alimenter le Workflow)',
    'sequence'    => 'TBL_SERVICE_SAISIE_ID_SEQ',
    'columns'     => [
        'ANNEE_ID'                => [
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
        'HEURES_REFERENTIEL_PREV' => [
            'name'        => 'HEURES_REFERENTIEL_PREV',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 2,
            'commentaire' => 'NB d\'heures de référentiel prévisionnel',
        ],
        'HEURES_REFERENTIEL_REAL' => [
            'name'        => 'HEURES_REFERENTIEL_REAL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 3,
            'commentaire' => 'NB d\'heures de référentiel réalisé',
        ],
        'HEURES_SERVICE_PREV'     => [
            'name'        => 'HEURES_SERVICE_PREV',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => 'NB d\'heures de service prévisionnel',
        ],
        'HEURES_SERVICE_REAL'     => [
            'name'        => 'HEURES_SERVICE_REAL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 5,
            'commentaire' => 'NB d\'heures de service réalisé',
        ],
        'ID'                      => [
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
        'INTERVENANT_ID'          => [
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
        'PEUT_SAISIR_REFERENTIEL' => [
            'name'        => 'PEUT_SAISIR_REFERENTIEL',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 8,
            'commentaire' => 'Témoin (0 ou 1)',
        ],
        'PEUT_SAISIR_SERVICE'     => [
            'name'        => 'PEUT_SAISIR_SERVICE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => 'Témoin (0 ou 1)',
        ],
        'TO_DELETE'               => [
            'name'        => 'TO_DELETE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 10,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
