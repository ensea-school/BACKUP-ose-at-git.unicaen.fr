<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_ANNEE_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
