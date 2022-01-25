<?php

//@formatter:off

return [
    'name'        => 'TPJS_STATUT_INTERVENANT_FK',
    'table'       => 'TYPE_PIECE_JOINTE_STATUT',
    'rtable'      => 'STATUT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
