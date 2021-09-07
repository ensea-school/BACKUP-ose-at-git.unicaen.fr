<?php

//@formatter:off

return [
    'name'        => 'TBL_PAIEMENT_DF_FK',
    'table'       => 'TBL_PAIEMENT',
    'rtable'      => 'DOMAINE_FONCTIONNEL',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'DOMAINE_FONCTIONNEL_ID' => 'ID',
    ],
];

//@formatter:on
