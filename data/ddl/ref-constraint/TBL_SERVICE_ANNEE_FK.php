<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_ANNEE_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
