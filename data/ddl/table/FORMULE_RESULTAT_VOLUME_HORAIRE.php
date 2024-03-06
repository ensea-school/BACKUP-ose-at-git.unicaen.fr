<?php

//@formatter:off

return [
    'name'        => 'FORMULE_RESULTAT_VOLUME_HORAIRE',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => NULL,
    'sequence'    => NULL,
    'columns'     => [
        'FORMULE_RESULTAT_INTERVENANT_ID' => [
            'name'        => 'FORMULE_RESULTAT_INTERVENANT_ID',
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
        'HEURES'                          => [
            'name'        => 'HEURES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'HEURES_COMPL_FA'                 => [
            'name'        => 'HEURES_COMPL_FA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 34,
            'commentaire' => NULL,
        ],
        'HEURES_COMPL_FC'                 => [
            'name'        => 'HEURES_COMPL_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 35,
            'commentaire' => NULL,
        ],
        'HEURES_COMPL_FI'                 => [
            'name'        => 'HEURES_COMPL_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'HEURES_COMPL_REFERENTIEL'        => [
            'name'        => 'HEURES_COMPL_REFERENTIEL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 36,
            'commentaire' => NULL,
        ],
        'HEURES_NON_PAYABLE_FA'           => [
            'name'        => 'HEURES_NON_PAYABLE_FA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'HEURES_NON_PAYABLE_FC'           => [
            'name'        => 'HEURES_NON_PAYABLE_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'HEURES_NON_PAYABLE_FI'           => [
            'name'        => 'HEURES_NON_PAYABLE_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'HEURES_NON_PAYABLE_REFERENTIEL'  => [
            'name'        => 'HEURES_NON_PAYABLE_REFERENTIEL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'HEURES_PRIMES'                   => [
            'name'        => 'HEURES_PRIMES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 37,
            'commentaire' => NULL,
        ],
        'HEURES_SERVICE_FA'               => [
            'name'        => 'HEURES_SERVICE_FA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'HEURES_SERVICE_FC'               => [
            'name'        => 'HEURES_SERVICE_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'HEURES_SERVICE_FI'               => [
            'name'        => 'HEURES_SERVICE_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'HEURES_SERVICE_REFERENTIEL'      => [
            'name'        => 'HEURES_SERVICE_REFERENTIEL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'ID'                              => [
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
        'NON_PAYABLE'                     => [
            'name'        => 'NON_PAYABLE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'PARAM_1'                         => [
            'name'        => 'PARAM_1',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'PARAM_2'                         => [
            'name'        => 'PARAM_2',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'PARAM_3'                         => [
            'name'        => 'PARAM_3',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'PARAM_4'                         => [
            'name'        => 'PARAM_4',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'PARAM_5'                         => [
            'name'        => 'PARAM_5',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'PONDERATION_SERVICE_COMPL'       => [
            'name'        => 'PONDERATION_SERVICE_COMPL',
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
        'PONDERATION_SERVICE_DU'          => [
            'name'        => 'PONDERATION_SERVICE_DU',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'SERVICE_ID'                      => [
            'name'        => 'SERVICE_ID',
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
        'SERVICE_REFERENTIEL_ID'          => [
            'name'        => 'SERVICE_REFERENTIEL_ID',
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
        'SERVICE_STATUTAIRE'              => [
            'name'        => 'SERVICE_STATUTAIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'STRUCTURE_CODE'                  => [
            'name'        => 'STRUCTURE_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'STRUCTURE_UNIV'                  => [
            'name'        => 'STRUCTURE_UNIV',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'TAUX_FA'                         => [
            'name'        => 'TAUX_FA',
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
        'TAUX_FC'                         => [
            'name'        => 'TAUX_FC',
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
        'TAUX_FI'                         => [
            'name'        => 'TAUX_FI',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'TAUX_SERVICE_COMPL'              => [
            'name'        => 'TAUX_SERVICE_COMPL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'TAUX_SERVICE_DU'                 => [
            'name'        => 'TAUX_SERVICE_DU',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'TOTAL'                           => [
            'name'        => 'TOTAL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 38,
            'commentaire' => NULL,
        ],
        'TYPE_INTERVENTION_CODE'          => [
            'name'        => 'TYPE_INTERVENTION_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 15,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'VOLUME_HORAIRE_ID'               => [
            'name'        => 'VOLUME_HORAIRE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'VOLUME_HORAIRE_REF_ID'           => [
            'name'        => 'VOLUME_HORAIRE_REF_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
