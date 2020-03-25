<?php

//@formatter:off

return [
    'name'        => 'STATUT_INTERVENANT_SOURCE_FK',
    'table'       => 'STATUT_INTERVENANT',
    'rtable'      => 'SOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SOURCE_ID' => 'ID',
    ],
];

//@formatter:on
