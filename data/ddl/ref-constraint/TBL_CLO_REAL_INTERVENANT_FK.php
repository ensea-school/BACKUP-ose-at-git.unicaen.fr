<?php

//@formatter:off

return [
    'name'        => 'TBL_CLO_REAL_INTERVENANT_FK',
    'table'       => 'TBL_CLOTURE_REALISE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
