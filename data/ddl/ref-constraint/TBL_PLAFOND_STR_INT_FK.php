<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_STR_INT_FK',
    'table'       => 'TBL_PLAFOND_STRUCTURE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
