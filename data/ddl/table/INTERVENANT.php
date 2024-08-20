<?php

//@formatter:off

return [
    'name'          => 'INTERVENANT',
    'temporary'     => FALSE,
    'logging'       => TRUE,
    'commentaire'   => 'columns-order=ID,ANNEE_ID,CODE,UTILISATEUR_CODE,STRUCTURE_ID,STATUT_ID,GRADE_ID,DISCIPLINE_ID,CIVILITE_ID,NOM_USUEL,PRENOM,DATE_NAISSANCE,NOM_PATRONYMIQUE,COMMUNE_NAISSANCE,PAYS_NAISSANCE_ID,DEPARTEMENT_NAISSANCE_ID,PAYS_NATIONALITE_ID,TEL_PRO,TEL_PERSO,EMAIL_PRO,EMAIL_PERSO,ADDR_PRECISIONS,ADDR_NUMERO,ADDR_NUMERO_COMPL_ID,ADDR_VOIRIE_ID,ADDR_VOIE,ADDR_LIEU_DIT,ADDR_CODE_POSTAL,ADDR_COMMUNE,ADDR_PAYS_ID,NUMERO_INSEE,NUMERO_INSEE_PROVISOIRE,IBAN,BIC,RIB_HORS_SEPA,AUTRE_1,AUTRE_2,AUTRE_3,AUTRE_4,AUTRE_5,EMPLOYEUR_ID,MONTANT_INDEMNITE_FC,CRITERE_RECHERCHE,SOURCE_ID,SOURCE_CODE,SYNC_STATUT,SYNC_STRUCTURE,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID;',
    'sequence'      => 'INTERVENANT_ID_SEQ',
    'columns'       => [
        'ADRESSE_CODE_POSTAL'         => [
            'name'        => 'ADRESSE_CODE_POSTAL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 15,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 29,
            'commentaire' => NULL,
        ],
        'ADRESSE_COMMUNE'             => [
            'name'        => 'ADRESSE_COMMUNE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 30,
            'commentaire' => NULL,
        ],
        'ADRESSE_LIEU_DIT'            => [
            'name'        => 'ADRESSE_LIEU_DIT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 28,
            'commentaire' => NULL,
        ],
        'ADRESSE_NUMERO'              => [
            'name'        => 'ADRESSE_NUMERO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 4,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 24,
            'commentaire' => NULL,
        ],
        'ADRESSE_NUMERO_COMPL_ID'     => [
            'name'        => 'ADRESSE_NUMERO_COMPL_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 25,
            'commentaire' => NULL,
        ],
        'ADRESSE_PAYS_ID'             => [
            'name'        => 'ADRESSE_PAYS_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 31,
            'commentaire' => NULL,
        ],
        'ADRESSE_PRECISIONS'          => [
            'name'        => 'ADRESSE_PRECISIONS',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 240,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 23,
            'commentaire' => NULL,
        ],
        'ADRESSE_VOIE'                => [
            'name'        => 'ADRESSE_VOIE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 27,
            'commentaire' => NULL,
        ],
        'ADRESSE_VOIRIE_ID'           => [
            'name'        => 'ADRESSE_VOIRIE_ID',
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
        'AFFECTATION_FIN'             => [
            'name'        => 'AFFECTATION_FIN',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 45,
            'commentaire' => NULL,
        ],
        'ANNEE_ID'                    => [
            'name'        => 'ANNEE_ID',
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
        'AUTRE_1'                     => [
            'name'        => 'AUTRE_1',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 37,
            'commentaire' => NULL,
        ],
        'AUTRE_2'                     => [
            'name'        => 'AUTRE_2',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 38,
            'commentaire' => NULL,
        ],
        'AUTRE_3'                     => [
            'name'        => 'AUTRE_3',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 39,
            'commentaire' => NULL,
        ],
        'AUTRE_4'                     => [
            'name'        => 'AUTRE_4',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 40,
            'commentaire' => NULL,
        ],
        'AUTRE_5'                     => [
            'name'        => 'AUTRE_5',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1000,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 41,
            'commentaire' => NULL,
        ],
        'BIC'                         => [
            'name'        => 'BIC',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 35,
            'commentaire' => NULL,
        ],
        'CIVILITE_ID'                 => [
            'name'        => 'CIVILITE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 10,
            'commentaire' => NULL,
        ],
        'CODE'                        => [
            'name'        => 'CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => NULL,
        ],
        'CODE_RH'                     => [
            'name'        => 'CODE_RH',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => NULL,
        ],
        'COMMUNE_NAISSANCE'           => [
            'name'        => 'COMMUNE_NAISSANCE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 15,
            'commentaire' => NULL,
        ],
        'CRITERE_RECHERCHE'           => [
            'name'        => 'CRITERE_RECHERCHE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 47,
            'commentaire' => NULL,
        ],
        'DATE_NAISSANCE'              => [
            'name'        => 'DATE_NAISSANCE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 13,
            'commentaire' => NULL,
        ],
        'DEPARTEMENT_NAISSANCE_ID'    => [
            'name'        => 'DEPARTEMENT_NAISSANCE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 17,
            'commentaire' => NULL,
        ],
        'DISCIPLINE_ID'               => [
            'name'        => 'DISCIPLINE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 9,
            'commentaire' => NULL,
        ],
        'EMAIL_PERSO'                 => [
            'name'        => 'EMAIL_PERSO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 255,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 22,
            'commentaire' => NULL,
        ],
        'EMAIL_PRO'                   => [
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
        'EMPLOYEUR_ID'                => [
            'name'        => 'EMPLOYEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 42,
            'commentaire' => NULL,
        ],
        'EXPORT_DATE'                 => [
            'name'        => 'EXPORT_DATE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 59,
            'commentaire' => 'Date du dernier export vers le SIRH',
        ],
        'GRADE_ID'                    => [
            'name'        => 'GRADE_ID',
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
        'HISTO_CREATEUR_ID'           => [
            'name'        => 'HISTO_CREATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 54,
            'commentaire' => NULL,
        ],
        'HISTO_CREATION'              => [
            'name'        => 'HISTO_CREATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 53,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTEUR_ID'        => [
            'name'        => 'HISTO_DESTRUCTEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 58,
            'commentaire' => NULL,
        ],
        'HISTO_DESTRUCTION'           => [
            'name'        => 'HISTO_DESTRUCTION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 57,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATEUR_ID'       => [
            'name'        => 'HISTO_MODIFICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 56,
            'commentaire' => NULL,
        ],
        'HISTO_MODIFICATION'          => [
            'name'        => 'HISTO_MODIFICATION',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => 'SYSDATE',
            'position'    => 55,
            'commentaire' => NULL,
        ],
        'IBAN'                        => [
            'name'        => 'IBAN',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 50,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 34,
            'commentaire' => NULL,
        ],
        'ID'                          => [
            'name'        => 'ID',
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
        'IRRECEVABLE'                 => [
            'name'        => 'IRRECEVABLE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 60,
            'commentaire' => NULL,
        ],
        'MONTANT_INDEMNITE_FC'        => [
            'name'        => 'MONTANT_INDEMNITE_FC',
            'type'        => 'float',
            'bdd-type'    => 'FLOAT',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 126,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 46,
            'commentaire' => NULL,
        ],
        'NOM_PATRONYMIQUE'            => [
            'name'        => 'NOM_PATRONYMIQUE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 14,
            'commentaire' => NULL,
        ],
        'NOM_USUEL'                   => [
            'name'        => 'NOM_USUEL',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 11,
            'commentaire' => NULL,
        ],
        'NUMERO_INSEE'                => [
            'name'        => 'NUMERO_INSEE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 20,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 32,
            'commentaire' => NULL,
        ],
        'NUMERO_INSEE_PROVISOIRE'     => [
            'name'        => 'NUMERO_INSEE_PROVISOIRE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 33,
            'commentaire' => NULL,
        ],
        'NUMERO_PEC'                  => [
            'name'        => 'NUMERO_PEC',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 61,
            'commentaire' => NULL,
        ],
        'PAYS_NAISSANCE_ID'           => [
            'name'        => 'PAYS_NAISSANCE_ID',
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
        'PAYS_NATIONALITE_ID'         => [
            'name'        => 'PAYS_NATIONALITE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 18,
            'commentaire' => NULL,
        ],
        'PRENOM'                      => [
            'name'        => 'PRENOM',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 12,
            'commentaire' => NULL,
        ],
        'RIB_HORS_SEPA'               => [
            'name'        => 'RIB_HORS_SEPA',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 36,
            'commentaire' => NULL,
        ],
        'SITUATION_MATRIMONIALE_DATE' => [
            'name'        => 'SITUATION_MATRIMONIALE_DATE',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 63,
            'commentaire' => NULL,
        ],
        'SITUATION_MATRIMONIALE_ID'   => [
            'name'        => 'SITUATION_MATRIMONIALE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 38,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 64,
            'commentaire' => NULL,
        ],
        'SOURCE_CODE'                 => [
            'name'        => 'SOURCE_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 100,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 49,
            'commentaire' => NULL,
        ],
        'SOURCE_ID'                   => [
            'name'        => 'SOURCE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 48,
            'commentaire' => NULL,
        ],
        'STATUT_ID'                   => [
            'name'        => 'STATUT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 7,
            'commentaire' => NULL,
        ],
        'STRUCTURE_ID'                => [
            'name'        => 'STRUCTURE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => 'Structure principale d\'affectation',
        ],
        'SYNC_PEC'                    => [
            'name'        => 'SYNC_PEC',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 62,
            'commentaire' => NULL,
        ],
        'SYNC_STATUT'                 => [
            'name'        => 'SYNC_STATUT',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 50,
            'commentaire' => NULL,
        ],
        'SYNC_STRUCTURE'              => [
            'name'        => 'SYNC_STRUCTURE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 51,
            'commentaire' => NULL,
        ],
        'SYNC_UTILISATEUR_CODE'       => [
            'name'        => 'SYNC_UTILISATEUR_CODE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '1',
            'position'    => 52,
            'commentaire' => NULL,
        ],
        'TEL_PERSO'                   => [
            'name'        => 'TEL_PERSO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 20,
            'commentaire' => NULL,
        ],
        'TEL_PRO'                     => [
            'name'        => 'TEL_PRO',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 30,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 19,
            'commentaire' => NULL,
        ],
        'UTILISATEUR_CODE'            => [
            'name'        => 'UTILISATEUR_CODE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 60,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'VALIDITE_DEBUT'              => [
            'name'        => 'VALIDITE_DEBUT',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 43,
            'commentaire' => NULL,
        ],
        'VALIDITE_FIN'                => [
            'name'        => 'VALIDITE_FIN',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 44,
            'commentaire' => NULL,
        ],
    ],
    'columns-order' => 'ID,ANNEE_ID,CODE,UTILISATEUR_CODE,STRUCTURE_ID,STATUT_ID,GRADE_ID,DISCIPLINE_ID,CIVILITE_ID,NOM_USUEL,PRENOM,DATE_NAISSANCE,NOM_PATRONYMIQUE,COMMUNE_NAISSANCE,PAYS_NAISSANCE_ID,DEPARTEMENT_NAISSANCE_ID,PAYS_NATIONALITE_ID,TEL_PRO,TEL_PERSO,EMAIL_PRO,EMAIL_PERSO,ADDR_PRECISIONS,ADDR_NUMERO,ADDR_NUMERO_COMPL_ID,ADDR_VOIRIE_ID,ADDR_VOIE,ADDR_LIEU_DIT,ADDR_CODE_POSTAL,ADDR_COMMUNE,ADDR_PAYS_ID,NUMERO_INSEE,NUMERO_INSEE_PROVISOIRE,IBAN,BIC,RIB_HORS_SEPA,AUTRE_1,AUTRE_2,AUTRE_3,AUTRE_4,AUTRE_5,EMPLOYEUR_ID,MONTANT_INDEMNITE_FC,CRITERE_RECHERCHE,SOURCE_ID,SOURCE_CODE,SYNC_STATUT,SYNC_STRUCTURE,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID',
];

//@formatter:on
