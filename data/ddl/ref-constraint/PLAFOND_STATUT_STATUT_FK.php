<?php

//@formatter:off

return [
    'name'        => 'PLAFOND_STATUT_STATUT_FK',
    'table'       => 'PLAFOND_STATUT',
    'rtable'      => 'STATUT_INTERVENANT',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
