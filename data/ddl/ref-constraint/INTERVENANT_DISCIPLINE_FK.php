<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_DISCIPLINE_FK',
    'table'       => 'INTERVENANT',
    'rtable'      => 'DISCIPLINE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'DISCIPLINE_ID' => 'ID',
    ],
];

//@formatter:on
