<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_REF_INTERVENANT_FK',
    'table'       => 'TBL_SERVICE_REFERENTIEL',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
