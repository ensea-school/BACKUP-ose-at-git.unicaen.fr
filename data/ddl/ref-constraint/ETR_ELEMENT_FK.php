<?php

//@formatter:off

return [
    'name'        => 'ETR_ELEMENT_FK',
    'table'       => 'ELEMENT_TAUX_REGIMES',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
