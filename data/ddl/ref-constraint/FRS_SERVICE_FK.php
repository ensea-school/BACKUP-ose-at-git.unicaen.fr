<?php

//@formatter:off

return [
    'name'        => 'FRS_SERVICE_FK',
    'table'       => 'FORMULE_RESULTAT_SERVICE',
    'rtable'      => 'SERVICE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_ID' => 'ID',
    ],
];

//@formatter:on
