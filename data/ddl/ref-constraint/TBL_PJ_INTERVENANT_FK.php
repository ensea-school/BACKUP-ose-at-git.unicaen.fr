<?php

//@formatter:off

return [
    'name'        => 'TBL_PJ_INTERVENANT_FK',
    'table'       => 'TBL_PIECE_JOINTE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
