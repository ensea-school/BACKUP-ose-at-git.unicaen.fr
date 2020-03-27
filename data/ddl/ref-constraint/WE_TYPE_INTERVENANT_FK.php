<?php

//@formatter:off

return [
    'name'        => 'WE_TYPE_INTERVENANT_FK',
    'table'       => 'WF_ETAPE_DEP',
    'rtable'      => 'TYPE_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
