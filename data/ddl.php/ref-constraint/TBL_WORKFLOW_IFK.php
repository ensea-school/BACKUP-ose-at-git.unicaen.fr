<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_IFK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
