<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_INTERVENANT_INT_FK',
    'table'       => 'TBL_PLAFOND_INTERVENANT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
