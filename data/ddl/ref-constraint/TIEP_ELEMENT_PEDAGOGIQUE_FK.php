<?php

//@formatter:off

return [
    'name'        => 'TIEP_ELEMENT_PEDAGOGIQUE_FK',
    'table'       => 'TYPE_INTERVENTION_EP',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
