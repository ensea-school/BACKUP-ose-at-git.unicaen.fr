<?php

//@formatter:off

return [
    'name'        => 'INDIC_DIFF_DOSSIER_INT_FK',
    'table'       => 'INDIC_MODIF_DOSSIER',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
