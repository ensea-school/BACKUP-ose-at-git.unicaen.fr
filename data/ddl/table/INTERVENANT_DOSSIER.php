<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_DOSSIER',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'INTERVENANT_DOSSIER_ID_SEQ',
    'columns'     => [
        'ADRESSE_CODE_POSTAL'      => [
            'name'        => 'ADRESSE_CODE_POSTAL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 15,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'ADRESSE_COMMUNE'          => [
            'name'        => 'ADRESSE_COMMUNE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'ADRESSE_LIEU_DIT'         => [
            'name'        => 'ADRESSE_LIEU_DIT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'ADRESSE_NUMERO'           => [
            'name'        => 'ADRESSE_NUMERO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'ADRESSE_NUMERO_COMPL_ID'  => [
            'name'        => 'ADRESSE_NUMERO_COMPL_ID',
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
        'ADRESSE_PAYS_ID'          => [
            'name'        => 'ADRESSE_PAYS_ID',
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
        'ADRESSE_PRECISIONS'       => [
            'name'        => 'ADRESSE_PRECISIONS',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 240,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'ADRESSE_VOIE'             => [
            'name'        => 'ADRESSE_VOIE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'ADRESSE_VOIRIE_ID'        => [
            'name'        => 'ADRESSE_VOIRIE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'AUTRE_1'                  => [
            'name'        => 'AUTRE_1',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'AUTRE_2'                  => [
            'name'        => 'AUTRE_2',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'AUTRE_3'                  => [
            'name'        => 'AUTRE_3',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'AUTRE_4'                  => [
            'name'        => 'AUTRE_4',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'AUTRE_5'                  => [
            'name'        => 'AUTRE_5',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'BIC'                      => [
            'name'        => 'BIC',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'CIVILITE_ID'              => [
            'name'        => 'CIVILITE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'COMMUNE_NAISSANCE'        => [
            'name'        => 'COMMUNE_NAISSANCE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'DATE_NAISSANCE'           => [
            'name'        => 'DATE_NAISSANCE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'DEPARTEMENT_NAISSANCE_ID' => [
            'name'        => 'DEPARTEMENT_NAISSANCE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'EMAIL_PERSO'              => [
            'name'        => 'EMAIL_PERSO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'EMAIL_PRO'                => [
            'name'        => 'EMAIL_PRO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'EMPLOYEUR_ID'             => [
            'name'        => 'EMPLOYEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'HISTO_CREATEUR_ID'        => [
            'name'        => 'HISTO_CREATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'HISTO_CREATION'           => [
            'name'        => 'HISTO_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTEUR_ID'     => [
            'name'        => 'HISTO_DESTRUCTEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTION'        => [
            'name'        => 'HISTO_DESTRUCTION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATEUR_ID'    => [
            'name'        => 'HISTO_MODIFICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATION'       => [
            'name'        => 'HISTO_MODIFICATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'IBAN'                     => [
            'name'        => 'IBAN',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 29,
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
            'default'     => NULL,
            'position'    => 30,
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
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'NOM_PATRONYMIQUE'         => [
            'name'        => 'NOM_PATRONYMIQUE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'NOM_USUEL'                => [
            'name'        => 'NOM_USUEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'NUMERO_INSEE'             => [
            'name'        => 'NUMERO_INSEE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 34,
            'commentaire' => NULL,
        ],
        'NUMERO_INSEE_PROVISOIRE'  => [
            'name'        => 'NUMERO_INSEE_PROVISOIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 35,
            'commentaire' => NULL,
        ],
        'PAYS_NAISSANCE_ID'        => [
            'name'        => 'PAYS_NAISSANCE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 36,
            'commentaire' => NULL,
        ],
        'PAYS_NATIONALITE_ID'      => [
            'name'        => 'PAYS_NATIONALITE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 37,
            'commentaire' => NULL,
        ],
        'PRENOM'                   => [
            'name'        => 'PRENOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 38,
            'commentaire' => NULL,
        ],
        'RIB_HORS_SEPA'            => [
            'name'        => 'RIB_HORS_SEPA',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 39,
            'commentaire' => NULL,
        ],
        'STATUT_ID'                => [
            'name'        => 'STATUT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 40,
            'commentaire' => NULL,
        ],
        'TEL_PERSO'                => [
            'name'        => 'TEL_PERSO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 41,
            'commentaire' => NULL,
        ],
        'TEL_PRO'                  => [
            'name'        => 'TEL_PRO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 42,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
