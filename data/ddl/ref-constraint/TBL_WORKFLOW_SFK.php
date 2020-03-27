<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_SFK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
