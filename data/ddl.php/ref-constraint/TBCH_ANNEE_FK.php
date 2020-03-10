<?php

//@formatter:off

return [
    'name'        => 'TBCH_ANNEE_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
