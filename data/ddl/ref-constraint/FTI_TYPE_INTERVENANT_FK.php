<?php

//@formatter:off

return [
    'name'        => 'FTI_TYPE_INTERVENANT_FK',
    'table'       => 'FORMULE_TEST_INTERVENANT',
    'rtable'      => 'TYPE_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
