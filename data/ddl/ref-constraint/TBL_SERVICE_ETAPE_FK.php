<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_ETAPE_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'ETAPE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAPE_ID' => 'ID',
    ],
];

//@formatter:on
