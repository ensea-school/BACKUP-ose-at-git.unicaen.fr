<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_PAYS_NAT_FK',
    'table'       => 'INTERVENANT',
    'rtable'      => 'PAYS',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'PAYS_NATIONALITE_ID' => 'ID',
    ],
];

//@formatter:on
