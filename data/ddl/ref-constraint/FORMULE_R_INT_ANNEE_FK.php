<?php

//@formatter:off

return [
    'name'        => 'FORMULE_R_INT_ANNEE_FK',
    'table'       => 'FORMULE_RESULTAT_INTERVENANT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
