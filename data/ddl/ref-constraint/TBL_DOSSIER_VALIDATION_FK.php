<?php

//@formatter:off

return [
    'name'        => 'TBL_DOSSIER_VALIDATION_FK',
    'table'       => 'TBL_DOSSIER',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
