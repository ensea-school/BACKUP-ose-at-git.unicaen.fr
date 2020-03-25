<?php

//@formatter:off

return [
    'name'        => 'FTI_ANNEE_FK',
    'table'       => 'FORMULE_TEST_INTERVENANT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
