<?php

//@formatter:off

return [
    'name'        => 'TBL_DOSSIER',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Données personnelles',
    'sequence'    => 'TBL_DOSSIER_ID_SEQ',
    'columns'     => [
        'ACTIF'                    => [
            'name'        => 'ACTIF',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => '1 Si l\'intervenant a un dossier, 0 sinon',
        ],
        'ANNEE_ID'                 => [
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
        'COMPLETUDE_ADRESSE'       => [
            'name'        => 'COMPLETUDE_ADRESSE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_AUTRES'        => [
            'name'        => 'COMPLETUDE_AUTRES',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_BANQUE'        => [
            'name'        => 'COMPLETUDE_BANQUE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_CONTACT'       => [
            'name'        => 'COMPLETUDE_CONTACT',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_EMPLOYEUR'     => [
            'name'        => 'COMPLETUDE_EMPLOYEUR',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_IDENTITE'      => [
            'name'        => 'COMPLETUDE_IDENTITE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_IDENTITE_COMP' => [
            'name'        => 'COMPLETUDE_IDENTITE_COMP',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_INSEE'         => [
            'name'        => 'COMPLETUDE_INSEE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'COMPLETUDE_STATUT'        => [
            'name'        => 'COMPLETUDE_STATUT',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => '0',
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'DOSSIER_ID'               => [
            'name'        => 'DOSSIER_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'ID'                       => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '"TBL_DOSSIER_ID_SEQ"."NEXTVAL"',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'           => [
            'name'        => 'INTERVENANT_ID',
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
        'VALIDATION_ID'            => [
            'name'        => 'VALIDATION_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
