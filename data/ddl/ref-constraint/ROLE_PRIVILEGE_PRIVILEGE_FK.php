<?php

//@formatter:off

return [
    'name'        => 'ROLE_PRIVILEGE_PRIVILEGE_FK',
    'table'       => 'ROLE_PRIVILEGE',
    'rtable'      => 'PRIVILEGE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PRIVILEGE_ID' => 'ID',
    ],
];

//@formatter:on
