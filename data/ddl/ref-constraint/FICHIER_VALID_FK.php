<?php

//@formatter:off

return [
    'name'        => 'FICHIER_VALID_FK',
    'table'       => 'FICHIER',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
