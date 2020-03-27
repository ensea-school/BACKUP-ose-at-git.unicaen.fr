<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_FRSR_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'FORMULE_RESULTAT_SERVICE_REF',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'FORMULE_RES_SERVICE_REF_ID' => 'ID',
    ],
];

//@formatter:on
