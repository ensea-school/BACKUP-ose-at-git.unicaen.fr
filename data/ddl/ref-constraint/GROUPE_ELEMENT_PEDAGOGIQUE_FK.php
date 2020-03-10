<?php

//@formatter:off

return [
    'name'        => 'GROUPE_ELEMENT_PEDAGOGIQUE_FK',
    'table'       => 'GROUPE',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
