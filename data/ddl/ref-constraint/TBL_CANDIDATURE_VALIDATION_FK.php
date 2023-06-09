<?php

//@formatter:off

return [
    'name'        => 'TBL_CANDIDATURE_VALIDATION_FK',
    'table'       => 'TBL_CANDIDATURE',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
