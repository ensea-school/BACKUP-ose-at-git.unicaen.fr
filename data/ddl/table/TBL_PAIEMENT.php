<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Données liées aux paiements et demandes de mises en paiement',
    'sequence'    => 'TBL_PAIEMENT_ID_SEQ',
    'columns'     => [
        'ANNEE_ID'                   => [
            'name'        => 'ANNEE_ID',
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
        'CENTRE_COUT_ID'             => [
            'name'        => 'CENTRE_COUT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'DOMAINE_FONCTIONNEL_ID'     => [
            'name'        => 'DOMAINE_FONCTIONNEL_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'FORMULE_RES_SERVICE_ID'     => [
            'name'        => 'FORMULE_RES_SERVICE_ID',
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
        'FORMULE_RES_SERVICE_REF_ID' => [
            'name'        => 'FORMULE_RES_SERVICE_REF_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'HEURES_A_PAYER_AA'          => [
            'name'        => 'HEURES_A_PAYER_AA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 21,
            'commentaire' => 'HETD à payer en
 AA',
        ],
        'HEURES_A_PAYER_AC'          => [
            'name'        => 'HEURES_A_PAYER_AC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 1,
            'commentaire' => 'HETD à payer en AC',
        ],
        'HEURES_DEMANDEES_AA'        => [
            'name'        => 'HEURES_DEMANDEES_AA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 22,
            'commentaire' => 'HETD demandées en AA',
        ],
        'HEURES_DEMANDEES_AC'        => [
            'name'        => 'HEURES_DEMANDEES_AC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 25,
            'commentaire' => 'HETD demandées en AC
',
        ],
        'HEURES_PAYEES_AA'           => [
            'name'        => 'HEURES_PAYEES_AA',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 23,
            'commentaire' => 'HETD payées en AA',
        ],
        'HEURES_PAYEES_AC'           => [
            'name'        => 'HEURES_PAYEES_AC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 26,
            'commentaire' => 'HETD payées en AC',
        ],
        'ID'                         => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => '"TBL_PAIEMENT_ID_SEQ"."NEXTVAL"',
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'             => [
            'name'        => 'INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'MISE_EN_PAIEMENT_ID'        => [
            'name'        => 'MISE_EN_PAIEMENT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'MISSION_ID'                 => [
            'name'        => 'MISSION_ID',
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
        'PERIODE_PAIEMENT_ID'        => [
            'name'        => 'PERIODE_PAIEMENT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'SERVICE_ID'                 => [
            'name'        => 'SERVICE_ID',
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
        'SERVICE_REFERENTIEL_ID'     => [
            'name'        => 'SERVICE_REFERENTIEL_ID',
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
        'STRUCTURE_ID'               => [
            'name'        => 'STRUCTURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'TAUX_CONGES_PAYES'          => [
            'name'        => 'TAUX_CONGES_PAYES',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'TAUX_HORAIRE'               => [
            'name'        => 'TAUX_HORAIRE',
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
        'TAUX_REMU_ID'               => [
            'name'        => 'TAUX_REMU_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
