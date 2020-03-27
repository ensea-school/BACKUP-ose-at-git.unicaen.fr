<?php

//@formatter:off

return [
    'name'        => 'DOTATION_STRUCTURE_FK',
    'table'       => 'DOTATION',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
