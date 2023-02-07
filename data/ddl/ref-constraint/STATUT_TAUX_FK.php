<?php

//@formatter:off

return [
    'name'        => 'STATUT_TAUX_FK',
    'table'       => 'STATUT',
    'rtable'      => 'TAUX_REMU',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'TAUX_REMU_ID' => 'ID',
    ],
];

//@formatter:on
