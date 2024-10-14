<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_SERVICE_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'SERVICE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_ID' => 'ID',
    ],
];

//@formatter:on