<?php

//@formatter:off

return [
    'name'        => 'TIS_STATUT_INTERVENANT_FK',
    'table'       => 'TYPE_INTERVENTION_STATUT',
    'rtable'      => 'STATUT_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
