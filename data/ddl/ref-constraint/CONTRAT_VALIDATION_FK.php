<?php

//@formatter:off

return [
    'name'        => 'CONTRAT_VALIDATION_FK',
    'table'       => 'CONTRAT',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
