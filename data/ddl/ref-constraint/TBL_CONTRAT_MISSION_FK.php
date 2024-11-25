<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_MISSION_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'MISSION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'MISSION_ID' => 'ID',
    ],
];

//@formatter:on
