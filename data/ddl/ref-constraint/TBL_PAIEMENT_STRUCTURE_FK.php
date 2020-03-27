<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_STRUCTURE_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
