<?php

//@formatter:off

return [
    'name'        => 'MISSION_INTERVENANT_FK',
    'table'       => 'MISSION',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
