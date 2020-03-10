<?php

//@formatter:off

return [
    'name'        => 'STAT_PRIV_PRIVILEGE_FK',
    'table'       => 'STATUT_PRIVILEGE',
    'rtable'      => 'PRIVILEGE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PRIVILEGE_ID' => 'ID',
    ],
];

//@formatter:on
