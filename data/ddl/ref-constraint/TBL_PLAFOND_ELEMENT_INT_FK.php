<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_ELEMENT_INT_FK',
    'table'       => 'TBL_PLAFOND_ELEMENT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
