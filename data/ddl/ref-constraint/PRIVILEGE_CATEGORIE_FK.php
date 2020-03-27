<?php

//@formatter:off

return [
    'name'        => 'PRIVILEGE_CATEGORIE_FK',
    'table'       => 'PRIVILEGE',
    'rtable'      => 'CATEGORIE_PRIVILEGE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'CATEGORIE_ID' => 'ID',
    ],
];

//@formatter:on
