<?php

//@formatter:off

return [
    'name'        => 'MEP_TYPE_HEURES_FK',
    'table'       => 'MISE_EN_PAIEMENT',
    'rtable'      => 'TYPE_HEURES',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_HEURES_ID' => 'ID',
    ],
];

//@formatter:on
