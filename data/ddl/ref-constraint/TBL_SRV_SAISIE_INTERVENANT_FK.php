<?php

//@formatter:off

return [
    'name'        => 'TBL_SRV_SAISIE_INTERVENANT_FK',
    'table'       => 'TBL_SERVICE_SAISIE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
