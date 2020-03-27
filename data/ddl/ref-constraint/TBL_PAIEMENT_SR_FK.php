<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_SR_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'SERVICE_REFERENTIEL',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_REFERENTIEL_ID' => 'ID',
    ],
];

//@formatter:on
