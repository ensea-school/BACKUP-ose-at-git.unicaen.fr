<?php

//@formatter:off

return [
    'name'        => 'PAYS_SOURCE_FK',
    'table'       => 'PAYS',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
