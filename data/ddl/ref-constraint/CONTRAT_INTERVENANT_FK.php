<?php

//@formatter:off

return [
    'name'        => 'CONTRAT_INTERVENANT_FK',
    'table'       => 'CONTRAT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
