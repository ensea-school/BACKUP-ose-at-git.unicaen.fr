<?php

//@formatter:off

return [
    'name'        => 'TVE_SERVICE_FK',
    'table'       => 'TBL_VALIDATION_ENSEIGNEMENT',
    'rtable'      => 'SERVICE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_ID' => 'ID',
    ],
];

//@formatter:on
