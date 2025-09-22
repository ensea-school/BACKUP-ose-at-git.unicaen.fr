<?php

//@formatter:off

return [
    'name'        => 'WORKFLOW_ETAPE_DEPENDANCE_PRECEDANTE_FK',
    'table'       => 'WORKFLOW_ETAPE_DEPENDANCE',
    'rtable'      => 'WORKFLOW_ETAPE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'ETAPE_PRECEDANTE_ID' => 'ID',
    ],
];

//@formatter:on
