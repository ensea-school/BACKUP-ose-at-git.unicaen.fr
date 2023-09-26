<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_PERIODE_ENS_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'PERIODE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'PERIODE_ENS_ID' => 'ID',
    ],
];

//@formatter:on
