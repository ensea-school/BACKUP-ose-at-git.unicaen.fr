<?php

//@formatter:off

return [
    'name'        => 'TAS_STATUT_INTERVENANT_FK',
    'table'       => 'TYPE_AGREMENT_STATUT',
    'rtable'      => 'STATUT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
