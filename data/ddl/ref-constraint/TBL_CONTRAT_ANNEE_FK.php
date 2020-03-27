<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_ANNEE_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
