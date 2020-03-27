<?php

//@formatter:off

return [
    'name'        => 'STRUCTURE_SOURCE_FK',
    'table'       => 'STRUCTURE',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
