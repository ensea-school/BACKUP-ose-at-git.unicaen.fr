<?php

//@formatter:off

return [
    'name'        => 'TBL_SERVICE_PERIODE_EP_FK',
    'table'       => 'TBL_SERVICE',
    'rtable'      => 'PERIODE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_PERIODE_ID' => 'ID',
    ],
];

//@formatter:on
