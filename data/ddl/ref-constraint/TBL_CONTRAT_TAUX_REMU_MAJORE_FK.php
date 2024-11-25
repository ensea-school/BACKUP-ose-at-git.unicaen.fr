<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_TAUX_REMU_MAJORE_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'TAUX_REMU',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TAUX_REMU_MAJORE_ID' => 'ID',
    ],
];

//@formatter:on
