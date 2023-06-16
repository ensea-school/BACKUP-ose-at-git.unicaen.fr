<?php

//@formatter:off

return [
    'name'        => 'TBL_CANDIDATURE_STRUCTURE_FK',
    'table'       => 'TBL_CANDIDATURE',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
