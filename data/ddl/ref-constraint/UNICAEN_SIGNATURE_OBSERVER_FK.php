<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_OBSERVER_FK',
    'table'       => 'UNICAEN_SIGNATURE_OBSERVER',
    'rtable'      => 'UNICAEN_SIGNATURE_SIGNATURE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'SIGNATURE_ID' => 'ID',
    ],
];

//@formatter:on
