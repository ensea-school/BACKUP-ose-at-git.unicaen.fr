<?php

//@formatter:off

return [
    'name'        => 'AFFECTATION_ROLE_FK',
    'table'       => 'AFFECTATION',
    'rtable'      => 'ROLE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ROLE_ID' => 'ID',
    ],
];

//@formatter:on
