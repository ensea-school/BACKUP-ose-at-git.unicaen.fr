<?php

//@formatter:off

return [
    'name'        => 'TBL_AGR_INTERVENANT_FK',
    'table'       => 'TBL_AGREMENT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
