<?php

//@formatter:off

return [
    'name'        => 'INTERVENANT_EMPLOYEUR_FK',
    'table'       => 'INTERVENANT',
    'rtable'      => 'EMPLOYEUR',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'EMPLOYEUR_ID' => 'ID',
    ],
];

//@formatter:on
