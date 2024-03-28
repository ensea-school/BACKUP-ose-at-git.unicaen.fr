<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Contrats de travail',
    'sequence'    => 'TBL_CONTRAT_ID_SEQ',
    'columns'     => [
        'ACTIF'                   => [
            'name'        => 'ACTIF',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'ANNEE_ID'                => [
            'name'        => 'ANNEE_ID',
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
        'AUTRE'                   => [
            'name'        => 'AUTRE',
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
        'AUTRE_LIBELLE'           => [
            'name'        => 'AUTRE_LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1024,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'CM'                      => [
            'name'        => 'CM',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => 0.0,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'CONTRAT_ID'              => [
            'name'        => 'CONTRAT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'CONTRAT_PARENT_ID'       => [
            'name'        => 'CONTRAT_PARENT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'DATE_CREATION'           => [
            'name'        => 'DATE_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'DATE_DEBUT'              => [
            'name'        => 'DATE_DEBUT',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'DATE_FIN'                => [
            'name'        => 'DATE_FIN',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'EDITE'                   => [
            'name'        => 'EDITE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'HETD'                    => [
            'name'        => 'HETD',
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
        'HEURES'                  => [
            'name'        => 'HEURES',
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
        'ID'                      => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'          => [
            'name'        => 'INTERVENANT_ID',
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
        'MISSION_ID'              => [
            'name'        => 'MISSION_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'SERVICE_ID'              => [
            'name'        => 'SERVICE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'SERVICE_REFERENTIEL_ID'  => [
            'name'        => 'SERVICE_REFERENTIEL_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'SIGNE'                   => [
            'name'        => 'SIGNE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'STRUCTURE_ID'            => [
            'name'        => 'STRUCTURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'TAUX_CONGES_PAYES'       => [
            'name'        => 'TAUX_CONGES_PAYES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_DATE'          => [
            'name'        => 'TAUX_REMU_DATE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_ID'            => [
            'name'        => 'TAUX_REMU_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_MAJORE_DATE'   => [
            'name'        => 'TAUX_REMU_MAJORE_DATE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_MAJORE_ID'     => [
            'name'        => 'TAUX_REMU_MAJORE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_MAJORE_VALEUR' => [
            'name'        => 'TAUX_REMU_MAJORE_VALEUR',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'TAUX_REMU_VALEUR'        => [
            'name'        => 'TAUX_REMU_VALEUR',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'TD'                      => [
            'name'        => 'TD',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => 0.0,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'TP'                      => [
            'name'        => 'TP',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => 0.0,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'TYPE_SERVICE'            => [
            'name'        => 'TYPE_SERVICE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 30,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
