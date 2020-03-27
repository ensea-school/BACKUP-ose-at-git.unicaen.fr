<?php

//@formatter:off

return [
    'name'        => 'ELEMENT_PEDAGOGIQUE_SOURCE_FK',
    'table'       => 'ELEMENT_PEDAGOGIQUE',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
