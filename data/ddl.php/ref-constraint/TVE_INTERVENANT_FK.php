<?php

//@formatter:off

return [
    'name'        => 'TVE_INTERVENANT_FK',
    'table'       => 'TBL_VALIDATION_ENSEIGNEMENT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
