<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_INTERVENANT_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
