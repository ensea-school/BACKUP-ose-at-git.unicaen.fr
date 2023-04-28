<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_MISSION_INT_FK',
    'table'       => 'TBL_PLAFOND_MISSION',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
