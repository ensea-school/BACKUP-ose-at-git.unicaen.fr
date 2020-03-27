<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_INTERVENANT_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
