<?php

//@formatter:off

return [
    'name'        => 'TBL_CHARGENS',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Charges d\'enseignement',
    'sequence'    => 'TBL_CHARGENS_ID_SEQ',
    'columns'     => [
        'ANNEE_ID'                 => [
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
        'ASSIDUITE'                => [
            'name'        => 'ASSIDUITE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => 'Taux d\'assiduité en % (entre 0 et 1)',
        ],
        'DEDOUBLEMENT'             => [
            'name'        => 'DEDOUBLEMENT',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => 'Seuil de dédoublement',
        ],
        'EFFECTIF'                 => [
            'name'        => 'EFFECTIF',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => 'Effectifs',
        ],
        'ELEMENT_PEDAGOGIQUE_ID'   => [
            'name'        => 'ELEMENT_PEDAGOGIQUE_ID',
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
        'ETAPE_ENS_ID'             => [
            'name'        => 'ETAPE_ENS_ID',
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
        'ETAPE_ID'                 => [
            'name'        => 'ETAPE_ID',
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
        'GROUPES'                  => [
            'name'        => 'GROUPES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => 'Nombre de groupes calculé',
        ],
        'GROUPE_TYPE_FORMATION_ID' => [
            'name'        => 'GROUPE_TYPE_FORMATION_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'HETD'                     => [
            'name'        => 'HETD',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => 'HETD réelles calculées',
        ],
        'HEURES'                   => [
            'name'        => 'HEURES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => 'Heures réelles calculées',
        ],
        'HEURES_ENS'               => [
            'name'        => 'HEURES_ENS',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => 'Heures d\'enseignement (charges par groupe)',
        ],
        'ID'                       => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'NOEUD_ID'                 => [
            'name'        => 'NOEUD_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'OUVERTURE'                => [
            'name'        => 'OUVERTURE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => 'Seuil d\'ouverture',
        ],
        'SCENARIO_ID'              => [
            'name'        => 'SCENARIO_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'STRUCTURE_ID'             => [
            'name'        => 'STRUCTURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'TYPE_HEURES_ID'           => [
            'name'        => 'TYPE_HEURES_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 19,
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
            'position'    => 20,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
