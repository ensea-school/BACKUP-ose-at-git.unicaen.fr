<?php

//@formatter:off

return [
    'name'        => 'STATUT_INTERVENANT',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'STATUT_INTERVENANT_ID_SEQ',
    'columns'     => [
        'CHARGES_PATRONALES'             => [
            'name'        => 'CHARGES_PATRONALES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 1,
            'commentaire' => NULL,
        ],
        'CODE'                           => [
            'name'        => 'CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_1'                => [
            'name'        => 'CODES_CORRESP_1',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_2'                => [
            'name'        => 'CODES_CORRESP_2',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_3'                => [
            'name'        => 'CODES_CORRESP_3',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'CODES_CORRESP_4'                => [
            'name'        => 'CODES_CORRESP_4',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => NULL,
        ],
        'DEPASSEMENT'                    => [
            'name'        => 'DEPASSEMENT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'DEPASSEMENT_SERVICE_DU_SANS_HC' => [
            'name'        => 'DEPASSEMENT_SERVICE_DU_SANS_HC',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 8,
            'commentaire' => NULL,
        ],
        'DOSSIER_ADRESSE'                => [
            'name'        => 'DOSSIER_ADRESSE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'DOSSIER_CONTACT'                => [
            'name'        => 'DOSSIER_CONTACT',
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
        'DOSSIER_EMPLOYEUR'              => [
            'name'        => 'DOSSIER_EMPLOYEUR',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'DOSSIER_IBAN'                   => [
            'name'        => 'DOSSIER_IBAN',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'DOSSIER_IDENTITE_COMP'               => [
            'name'        => 'DOSSIER_IDENTITE_COMP',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'DOSSIER_INSEE'                  => [
            'name'        => 'DOSSIER_INSEE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'DOSSIER_EMAIL_PERSO'                  => [
            'name'        => 'DOSSIER_EMAIL_PERSO',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'DOSSIER_TEL_PERSO'                  => [
            'name'        => 'DOSSIER_TEL_PERSO',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 16,
            'commentaire' => NULL,
        ],
        'HISTO_CREATEUR_ID'              => [
            'name'        => 'HISTO_CREATEUR_ID',
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
        'HISTO_CREATION'                 => [
            'name'        => 'HISTO_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTEUR_ID'           => [
            'name'        => 'HISTO_DESTRUCTEUR_ID',
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
        'HISTO_DESTRUCTION'              => [
            'name'        => 'HISTO_DESTRUCTION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATEUR_ID'          => [
            'name'        => 'HISTO_MODIFICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 21,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATION'             => [
            'name'        => 'HISTO_MODIFICATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'ID'                             => [
            'name'        => 'ID',
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
        'LIBELLE'                        => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 128,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'MAXIMUM_HETD'                   => [
            'name'        => 'MAXIMUM_HETD',
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
        'NON_AUTORISE'                   => [
            'name'        => 'NON_AUTORISE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 26,
            'commentaire' => NULL,
        ],
        'ORDRE'                          => [
            'name'        => 'ORDRE',
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
        'PEUT_AVOIR_CONTRAT'             => [
            'name'        => 'PEUT_AVOIR_CONTRAT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'PEUT_CHOISIR_DANS_DOSSIER'      => [
            'name'        => 'PEUT_CHOISIR_DANS_DOSSIER',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'PEUT_CLOTURER_SAISIE'           => [
            'name'        => 'PEUT_CLOTURER_SAISIE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'PEUT_SAISIR_DOSSIER'            => [
            'name'        => 'PEUT_SAISIR_DOSSIER',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'PEUT_SAISIR_MOTIF_NON_PAIEMENT' => [
            'name'        => 'PEUT_SAISIR_MOTIF_NON_PAIEMENT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'PEUT_SAISIR_REFERENTIEL'        => [
            'name'        => 'PEUT_SAISIR_REFERENTIEL',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'PEUT_SAISIR_SERVICE'            => [
            'name'        => 'PEUT_SAISIR_SERVICE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 34,
            'commentaire' => NULL,
        ],
        'PEUT_SAISIR_SERVICE_EXT'        => [
            'name'        => 'PEUT_SAISIR_SERVICE_EXT',
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
        'PLAFOND_HC_FI_HORS_EAD'         => [
            'name'        => 'PLAFOND_HC_FI_HORS_EAD',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '9999',
            'position'    => 36,
            'commentaire' => NULL,
        ],
        'PLAFOND_HC_HORS_REMU_FC'        => [
            'name'        => 'PLAFOND_HC_HORS_REMU_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '9999',
            'position'    => 37,
            'commentaire' => NULL,
        ],
        'PLAFOND_HC_REMU_FC'             => [
            'name'        => 'PLAFOND_HC_REMU_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '13502',
            'position'    => 38,
            'commentaire' => NULL,
        ],
        'PLAFOND_REFERENTIEL'            => [
            'name'        => 'PLAFOND_REFERENTIEL',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 39,
            'commentaire' => NULL,
        ],
        'SERVICE_STATUTAIRE'             => [
            'name'        => 'SERVICE_STATUTAIRE',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 40,
            'commentaire' => NULL,
        ],
        'TEM_ATV'                        => [
            'name'        => 'TEM_ATV',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 41,
            'commentaire' => NULL,
        ],
        'TEM_BIATSS'                     => [
            'name'        => 'TEM_BIATSS',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 42,
            'commentaire' => NULL,
        ],
        'TITULAIRE'                      => [
            'name'        => 'TITULAIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 43,
            'commentaire' => NULL,
        ],
        'TYPE_INTERVENANT_ID'            => [
            'name'        => 'TYPE_INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 44,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
