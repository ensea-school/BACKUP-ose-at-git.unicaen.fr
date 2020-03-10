<?php

//@formatter:off

return [
    'name'        => 'TPJS_ANNEE_FIN_FK',
    'table'       => 'TYPE_PIECE_JOINTE_STATUT',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_FIN_ID' => 'ID',
    ],
];

//@formatter:on
