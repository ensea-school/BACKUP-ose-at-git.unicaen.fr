<?php

//@formatter:off

return [
    'name'        => 'TBL_CANDIDATURE_INTERVENANT_FK',
    'table'       => 'TBL_CANDIDATURE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
