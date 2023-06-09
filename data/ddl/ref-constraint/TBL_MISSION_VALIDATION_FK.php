<?php

//@formatter:off

return [
    'name'        => 'TBL_MISSION_VALIDATION_FK',
    'table'       => 'TBL_MISSION',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
