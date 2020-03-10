<?php

//@formatter:off

return [
    'name'        => 'CENTRE_COUT_ACTIVITE_FK',
    'table'       => 'CENTRE_COUT',
    'rtable'      => 'CC_ACTIVITE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ACTIVITE_ID' => 'ID',
    ],
];

//@formatter:on
