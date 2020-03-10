<?php

//@formatter:off

return [
    'name'        => 'PLAFONDAPP_PLAFOND_FK',
    'table'       => 'PLAFOND_APPLICATION',
    'rtable'      => 'PLAFOND',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PLAFOND_ID' => 'ID',
    ],
];

//@formatter:on
