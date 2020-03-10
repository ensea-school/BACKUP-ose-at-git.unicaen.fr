<?php

//@formatter:off

return [
    'name'        => 'INTERDEF_INTERVENANT_FK',
    'table'       => 'INTERVENANT_PAR_DEFAUT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
