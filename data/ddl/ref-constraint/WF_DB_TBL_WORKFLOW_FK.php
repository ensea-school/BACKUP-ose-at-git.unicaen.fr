<?php

//@formatter:off

return [
    'name'        => 'WF_DB_TBL_WORKFLOW_FK',
    'table'       => 'WF_DEP_BLOQUANTE',
    'rtable'      => 'TBL_WORKFLOW',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TBL_WORKFLOW_ID' => 'ID',
    ],
];

//@formatter:on
