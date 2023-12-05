<?php

//@formatter:off

return [
    'name'        => 'TBL_MISSION_PRIME_INTERVENANT_FK',
    'table'       => 'TBL_MISSION_PRIME',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
