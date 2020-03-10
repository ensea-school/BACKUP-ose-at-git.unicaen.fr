<?php

//@formatter:off

return [
    'name'        => 'TBCH_ELEMENT_PEDAGOGIQUE_FK',
    'table'       => 'TBL_CHARGENS',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
