<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_STRUCTURE_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
