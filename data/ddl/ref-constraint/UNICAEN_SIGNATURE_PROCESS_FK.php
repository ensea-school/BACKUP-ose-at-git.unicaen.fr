<?php

//@formatter:off

return [
    'name'        => 'UNICAEN_SIGNATURE_PROCESS_FK',
    'table'       => 'UNICAEN_SIGNATURE_PROCESS',
    'rtable'      => 'UNICAEN_SIGNATURE_SIGNATUREFLOW',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'SIGNATUREFLOW_ID' => 'ID',
    ],
];

//@formatter:on
