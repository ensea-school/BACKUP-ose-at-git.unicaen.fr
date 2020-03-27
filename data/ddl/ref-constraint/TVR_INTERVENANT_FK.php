<?php

//@formatter:off

return [
    'name'        => 'TVR_INTERVENANT_FK',
    'table'       => 'TBL_VALIDATION_REFERENTIEL',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
