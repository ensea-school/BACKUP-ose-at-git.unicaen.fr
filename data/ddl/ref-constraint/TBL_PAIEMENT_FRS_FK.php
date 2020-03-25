<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_FRS_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'FORMULE_RESULTAT_SERVICE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'FORMULE_RES_SERVICE_ID' => 'ID',
    ],
];

//@formatter:on
