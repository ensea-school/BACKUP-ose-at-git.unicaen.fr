<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_SERVICE_REFERENTIEL_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'SERVICE_REFERENTIEL',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_REFERENTIEL_ID' => 'ID',
    ],
];

//@formatter:on
