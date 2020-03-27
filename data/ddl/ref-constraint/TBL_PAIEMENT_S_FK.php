<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_S_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'SERVICE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_ID' => 'ID',
    ],
];

//@formatter:on
