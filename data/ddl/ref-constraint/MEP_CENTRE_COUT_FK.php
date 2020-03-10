<?php

//@formatter:off

return [
    'name'        => 'MEP_CENTRE_COUT_FK',
    'table'       => 'MISE_EN_PAIEMENT',
    'rtable'      => 'CENTRE_COUT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'CENTRE_COUT_ID' => 'ID',
    ],
];

//@formatter:on
