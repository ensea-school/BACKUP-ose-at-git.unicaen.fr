<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_INTERVENANT_PLAFOND_FK',
    'table'       => 'TBL_PLAFOND_INTERVENANT',
    'rtable'      => 'PLAFOND',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PLAFOND_ID' => 'ID',
    ],
];

//@formatter:on
