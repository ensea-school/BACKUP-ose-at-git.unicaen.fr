<?php

//@formatter:off

return [
    'name'        => 'AGREMENT_INTERVENANT_FK',
    'table'       => 'AGREMENT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
