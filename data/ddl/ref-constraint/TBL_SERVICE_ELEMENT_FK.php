<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_ELEMENT_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
