<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_TINTERVENANT_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'TYPE_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
