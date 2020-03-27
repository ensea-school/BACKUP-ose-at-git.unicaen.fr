<?php

//@formatter:off

return [
    'name'        => 'VALIDATION_INTERVENANT_FK',
    'table'       => 'VALIDATION',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
