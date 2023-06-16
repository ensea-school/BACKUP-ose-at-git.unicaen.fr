<?php

//@formatter:off

return [
    'name'        => 'TBL_MISSION_CONTRAT_FK',
    'table'       => 'TBL_MISSION',
    'rtable'      => 'CONTRAT',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'CONTRAT_ID' => 'ID',
    ],
];

//@formatter:on
