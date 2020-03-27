<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_EFK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'WF_ETAPE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAPE_ID' => 'ID',
    ],
];

//@formatter:on
