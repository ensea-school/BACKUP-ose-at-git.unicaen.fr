<?php

//@formatter:off

return [
    'name'        => 'ETAPE_SOURCE_FK',
    'table'       => 'ETAPE',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
