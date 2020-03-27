<?php

//@formatter:off

return [
    'name'        => 'DOTATION_ANNEE_FK',
    'table'       => 'DOTATION',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
