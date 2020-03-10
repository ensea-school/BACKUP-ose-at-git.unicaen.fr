<?php

//@formatter:off

return [
    'name'        => 'ROLE_PRIVILEGE_ROLE_FK',
    'table'       => 'ROLE_PRIVILEGE',
    'rtable'      => 'ROLE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ROLE_ID' => 'ID',
    ],
];

//@formatter:on
