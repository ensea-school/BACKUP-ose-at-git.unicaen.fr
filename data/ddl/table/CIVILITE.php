<?php

//@formatter:off

return [
    'name'          => 'CIVILITE',
    'temporary'     => FALSE,
    'logging'       => TRUE,
    'commentaire'   => 'columns-order=ID,SEXE,LIBELLE_COURT,LIBELLE_LONG;
Liste des civilités',
    'sequence'      => 'CIVILITE_ID_SEQ',
    'columns'       => [
        'ID'            => [
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
        'LIBELLE_COURT' => [
            'name'        => 'LIBELLE_COURT',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 5,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'LIBELLE_LONG'  => [
            'name'        => 'LIBELLE_LONG',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 15,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'SEXE'          => [
            'name'        => 'SEXE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 1,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
    ],
<<<<<<< HEAD
    'columns-order' => 'ID,SEXE,LIBELLE_COURT,LIBELLE_LONG',
=======
>>>>>>> f7df416d25d66aefb986e919b4eeb0525a515765
];

//@formatter:on
