<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_TINTERVENANT_FK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'TYPE_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
