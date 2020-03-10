<?php

//@formatter:off

return [
    'name'        => 'CENTRE_COUT_TYPE_RESSOURCE_FK',
    'table'       => 'CENTRE_COUT',
    'rtable'      => 'TYPE_RESSOURCE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_RESSOURCE_ID' => 'ID',
    ],
];

//@formatter:on
