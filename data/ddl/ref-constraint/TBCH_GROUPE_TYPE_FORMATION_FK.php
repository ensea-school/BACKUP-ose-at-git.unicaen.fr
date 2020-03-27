<?php

//@formatter:off

return [
    'name'        => 'TBCH_GROUPE_TYPE_FORMATION_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'GROUPE_TYPE_FORMATION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'GROUPE_TYPE_FORMATION_ID' => 'ID',
    ],
];

//@formatter:on
