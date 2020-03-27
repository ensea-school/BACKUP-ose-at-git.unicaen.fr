<?php

//@formatter:off

return [
    'name'        => 'TVR_SERVICE_REFERENTIEL_FK',
    'table'       => 'TBL_VALIDATION_REFERENTIEL',
    'rtable'      => 'SERVICE_REFERENTIEL',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_REFERENTIEL_ID' => 'ID',
    ],
];

//@formatter:on
