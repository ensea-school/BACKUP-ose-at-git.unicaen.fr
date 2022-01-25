<?php

//@formatter:off

return [
    'name'        => 'TBL_WORKFLOW_STATUT_FK',
    'table'       => 'TBL_WORKFLOW',
    'rtable'      => 'STATUT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
