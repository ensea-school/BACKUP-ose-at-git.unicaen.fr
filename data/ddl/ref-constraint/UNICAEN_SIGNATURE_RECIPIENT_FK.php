<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_RECIPIENT_FK',
    'table'       => 'UNICAEN_SIGNATURE_RECIPIENT',
    'rtable'      => 'UNICAEN_SIGNATURE_SIGNATURE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'SIGNATURE_ID' => 'ID',
    ],
];

//@formatter:on
