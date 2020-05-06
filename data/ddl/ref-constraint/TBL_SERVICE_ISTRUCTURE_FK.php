<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_ISTRUCTURE_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'STRUCTURE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_STRUCTURE_ID' => 'ID',
    ],
];

//@formatter:on
