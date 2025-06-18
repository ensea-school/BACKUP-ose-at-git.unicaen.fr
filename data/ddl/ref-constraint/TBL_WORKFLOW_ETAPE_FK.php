<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_ETAPE_FK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'WORKFLOW_ETAPE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAPE_ID' => 'ID',
    ],
];

//@formatter:on
