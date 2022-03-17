<?php

//@formatter:off

return [
    'name'        => 'TYPE_FORMATION_GROUPE_FK',
    'table'       => 'TYPE_FORMATION',
    'rtable'      => 'GROUPE_TYPE_FORMATION',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'GROUPE_ID' => 'ID',
    ],
];

//@formatter:on
