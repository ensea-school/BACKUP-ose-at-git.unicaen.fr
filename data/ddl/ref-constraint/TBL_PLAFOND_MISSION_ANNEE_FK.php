<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_MISSION_ANNEE_FK',
    'table'       => 'TBL_PLAFOND_MISSION',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
