<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_ELEMENT_EP_FK',
    'table'       => 'TBL_PLAFOND_ELEMENT',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
