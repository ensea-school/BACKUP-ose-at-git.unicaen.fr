<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_MISSION_FR_FK',
    'table'       => 'TBL_PLAFOND_MISSION',
    'rtable'      => 'TYPE_MISSION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_MISSION_ID' => 'ID',
    ],
];

//@formatter:on
