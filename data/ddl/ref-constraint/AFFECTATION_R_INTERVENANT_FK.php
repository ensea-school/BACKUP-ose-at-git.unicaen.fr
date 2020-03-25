<?php

//@formatter:off

return [
    'name'        => 'AFFECTATION_R_INTERVENANT_FK',
    'table'       => 'AFFECTATION_RECHERCHE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
