<?php

//@formatter:off

return [
    'name'        => 'WORKFLOW_ETAPE_DEPENDANCE_TYPE_INTERVENANT_FK',
    'table'       => 'WORKFLOW_ETAPE_DEPENDANCE',
    'rtable'      => 'TYPE_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
