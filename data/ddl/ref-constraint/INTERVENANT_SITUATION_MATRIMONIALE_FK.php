<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_SITUATION_MATRIMONIALE_FK',
    'table'       => 'INTERVENANT',
    'rtable'      => 'SITUATION_MATRIMONIALE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'SITUATION_MATRIMONIALE_ID' => 'ID',
    ],
];

//@formatter:on
