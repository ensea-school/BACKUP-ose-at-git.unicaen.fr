<?php

//@formatter:off

return [
    'name'        => 'TBL_LIEN',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Liens (pour les charges d\'enseignement)',
    'sequence'    => 'TBL_LIEN_ID_SEQ',
    'columns'     => [
        'ACTIF'            => [
            'name'        => 'ACTIF',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'commentaire' => 'Témoin (0 ou 1), 1 si actif',
        ],
        'CHOIX_MAXIMUM'    => [
            'name'        => 'CHOIX_MAXIMUM',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => 'Choix maximum',
        ],
        'CHOIX_MINIMUM'    => [
            'name'        => 'CHOIX_MINIMUM',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => 'Choix minimum',
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
            'commentaire' => NULL,
        ],
        'LIEN_ID'          => [
            'name'        => 'LIEN_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'MAX_POIDS'        => [
            'name'        => 'MAX_POIDS',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'commentaire' => 'Poids maximum pour les fils',
        ],
        'NB_CHOIX'         => [
            'name'        => 'NB_CHOIX',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'commentaire' => 'Nombre de choix',
        ],
        'NOEUD_INF_ID'     => [
            'name'        => 'NOEUD_INF_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'NOEUD_SUP_ID'     => [
            'name'        => 'NOEUD_SUP_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'POIDS'            => [
            'name'        => 'POIDS',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'commentaire' => 'Poids (1 par défaut)',
        ],
        'SCENARIO_ID'      => [
            'name'        => 'SCENARIO_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'SCENARIO_LIEN_ID' => [
            'name'        => 'SCENARIO_LIEN_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'commentaire' => NULL,
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
            'commentaire' => NULL,
        ],
        'TOTAL_POIDS'      => [
            'name'        => 'TOTAL_POIDS',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'commentaire' => 'Total de poids des fils',
        ],
        'TO_DELETE'        => [
            'name'        => 'TO_DELETE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
