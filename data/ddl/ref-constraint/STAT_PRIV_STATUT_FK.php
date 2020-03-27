<?php

//@formatter:off

return [
    'name'        => 'STAT_PRIV_STATUT_FK',
    'table'       => 'STATUT_PRIVILEGE',
    'rtable'      => 'STATUT_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_ID' => 'ID',
    ],
];

//@formatter:on
