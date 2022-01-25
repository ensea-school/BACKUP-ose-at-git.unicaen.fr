<?php

//@formatter:off

return [
    'name'        => 'TI_STATUT_STATUT_INT_FK',
    'table'       => 'TYPE_INTERVENTION_STATUT',
    'rtable'      => 'STATUT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
