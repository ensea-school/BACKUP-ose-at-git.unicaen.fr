<?php

//@formatter:off

return [
    'name'        => 'CCEP_ELEMENT_PEDAGOGIQUE_FK',
    'table'       => 'CENTRE_COUT_EP',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
