<?php

//@formatter:off

return [
    'name'        => 'GROUPE_TYPE_FORMATIO_SOURCE_FK',
    'table'       => 'GROUPE_TYPE_FORMATION',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
