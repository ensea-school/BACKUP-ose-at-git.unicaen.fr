<?php

//@formatter:off

return [
    'name'        => 'CPEP_FK',
    'table'       => 'CHEMIN_PEDAGOGIQUE',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
