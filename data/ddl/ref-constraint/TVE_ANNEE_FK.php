<?php

//@formatter:off

return [
    'name'        => 'TVE_ANNEE_FK',
    'table'       => 'TBL_VALIDATION_ENSEIGNEMENT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
