<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_ANNEE_FK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
