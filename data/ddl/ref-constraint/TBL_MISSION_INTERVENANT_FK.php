<?php

//@formatter:off

return [
    'name'        => 'TBL_MISSION_INTERVENANT_FK',
    'table'       => 'TBL_MISSION',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
