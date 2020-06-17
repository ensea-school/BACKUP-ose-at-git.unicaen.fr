<?php

//@formatter:off

return [
    'name'        => 'FORMULE_TEST_STRUCTURE',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => 'sequence=FTEST_STRUCTURE_ID_SEQ;',
    'sequence'    => 'FTEST_STRUCTURE_ID_SEQ',
    'columns'     => [
        'ID'         => [
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
        'LIBELLE'    => [
            'name'        => 'LIBELLE',
            'type'        => 'string',
            'bdd-type'    => 'VARCHAR2',
            'length'      => 80,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => NULL,
        ],
        'UNIVERSITE' => [
            'name'        => 'UNIVERSITE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 3,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
