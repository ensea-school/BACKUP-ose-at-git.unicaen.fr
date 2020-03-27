<?php

//@formatter:off

return [
    'name'        => 'NOEUD_ELEMENT_PEDAGOGIQUE_FK',
    'table'       => 'NOEUD',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
