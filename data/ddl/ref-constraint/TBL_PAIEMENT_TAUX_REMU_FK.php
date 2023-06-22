<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_TAUX_REMU_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'TAUX_REMU',
    'delete_rule' => 'SET NULL',
    'index'       => NULL,
    'columns'     => [
        'TAUX_REMU_ID' => 'ID',
    ],
];

//@formatter:on
