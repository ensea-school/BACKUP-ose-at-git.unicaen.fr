<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_CONTRAT_PARENT_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'CONTRAT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'CONTRAT_PARENT_ID' => 'ID',
    ],
];

//@formatter:on
