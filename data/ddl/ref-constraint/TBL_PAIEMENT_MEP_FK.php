<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_MEP_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'MISE_EN_PAIEMENT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'MISE_EN_PAIEMENT_ID' => 'ID',
    ],
];

//@formatter:on
