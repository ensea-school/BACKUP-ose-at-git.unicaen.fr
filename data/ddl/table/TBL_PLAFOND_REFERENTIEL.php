<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_REFERENTIEL',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Plafond des intervenants',
    'sequence'    => 'TBL_PLAFOND_REFERENTIEL_ID_SEQ',
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
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'DEROGATION'              => [
            'name'        => 'DEROGATION',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 5,
            'commentaire' => 'Dérogation',
        ],
        'FONCTION_REFERENTIEL_ID' => [
            'name'        => 'FONCTION_REFERENTIEL_ID',
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
        'HEURES'                  => [
            'name'        => 'HEURES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 6,
            'commentaire' => 'Nombre d\'heures pour l\'intervenant concerné',
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
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'PLAFOND'                 => [
            'name'        => 'PLAFOND',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 7,
            'commentaire' => 'Valeur du plafond',
        ],
        'PLAFOND_ID'              => [
            'name'        => 'PLAFOND_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'TYPE_VOLUME_HORAIRE_ID'  => [
            'name'        => 'TYPE_VOLUME_HORAIRE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
