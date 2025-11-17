<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_VALIDATION_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
