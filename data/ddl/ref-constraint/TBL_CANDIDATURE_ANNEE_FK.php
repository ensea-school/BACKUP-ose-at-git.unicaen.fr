<?php

//@formatter:off

return [
    'name'        => 'TBL_CANDIDATURE_ANNEE_FK',
    'table'       => 'TBL_CANDIDATURE',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
