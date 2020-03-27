<?php

//@formatter:off

return [
    'name'        => 'TBCH_STRUCTURE_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
