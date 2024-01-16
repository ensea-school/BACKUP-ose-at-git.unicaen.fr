<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_TYPE_INT_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'TYPE_INTERVENANT',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'TYPE_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
