<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_INTERVENANT_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
