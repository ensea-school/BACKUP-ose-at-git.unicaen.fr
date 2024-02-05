<?php

//@formatter:off

return [
    'name'        => 'FRS_FORMULE_RESULTAT_FK',
    'table'       => 'FORMULE_RESULTAT_SERVICE',
    'rtable'      => 'FORMULE_RESULTAT_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'FORMULE_RESULTAT_ID' => 'ID',
    ],
];

//@formatter:on
