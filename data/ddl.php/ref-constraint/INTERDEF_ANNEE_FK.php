<?php

//@formatter:off

return [
    'name'        => 'INTERDEF_ANNEE_FK',
    'table'       => 'INTERVENANT_PAR_DEFAUT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
