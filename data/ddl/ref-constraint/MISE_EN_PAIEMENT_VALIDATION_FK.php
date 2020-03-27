<?php

//@formatter:off

return [
    'name'        => 'MISE_EN_PAIEMENT_VALIDATION_FK',
    'table'       => 'MISE_EN_PAIEMENT',
    'rtable'      => 'VALIDATION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VALIDATION_ID' => 'ID',
    ],
];

//@formatter:on
