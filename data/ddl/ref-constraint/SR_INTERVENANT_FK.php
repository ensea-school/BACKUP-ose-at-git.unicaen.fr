<?php

//@formatter:off

return [
    'name'        => 'SR_INTERVENANT_FK',
    'table'       => 'SERVICE_REFERENTIEL',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
