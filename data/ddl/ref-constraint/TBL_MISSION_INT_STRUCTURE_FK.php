<?php

//@formatter:off

return [
    'name'        => 'TBL_MISSION_INT_STRUCTURE_FK',
    'table'       => 'TBL_MISSION',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
