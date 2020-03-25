<?php

//@formatter:off

return [
    'name'        => 'DEPARTEMENT_SOURCE_FK',
    'table'       => 'DEPARTEMENT',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
