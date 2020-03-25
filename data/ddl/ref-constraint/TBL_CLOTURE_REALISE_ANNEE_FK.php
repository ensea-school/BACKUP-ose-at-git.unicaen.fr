<?php

//@formatter:off

return [
    'name'        => 'TBL_CLOTURE_REALISE_ANNEE_FK',
    'table'       => 'TBL_CLOTURE_REALISE',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
