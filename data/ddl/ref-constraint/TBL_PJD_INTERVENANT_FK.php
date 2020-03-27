<?php

//@formatter:off

return [
    'name'        => 'TBL_PJD_INTERVENANT_FK',
    'table'       => 'TBL_PIECE_JOINTE_DEMANDE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
