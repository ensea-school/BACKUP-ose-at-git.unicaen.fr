<?php

//@formatter:off

return [
    'name'        => 'TBL_MISSION_STRUCTURE_FK',
    'table'       => 'TBL_MISSION',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
