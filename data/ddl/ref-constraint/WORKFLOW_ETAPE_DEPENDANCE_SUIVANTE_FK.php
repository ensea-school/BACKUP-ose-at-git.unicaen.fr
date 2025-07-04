<?php

//@formatter:off

return [
    'name'        => 'WORKFLOW_ETAPE_DEPENDANCE_SUIVANTE_FK',
    'table'       => 'WORKFLOW_ETAPE_DEPENDANCE',
    'rtable'      => 'WORKFLOW_ETAPE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'ETAPE_SUIVANTE_ID' => 'ID',
    ],
];

//@formatter:on
