<?php

//@formatter:off

return [
    'name'        => 'STATUT_INTERVENANT_MAPPING_FK',
    'table'       => 'STATUT_INTERVENANT_MAPPING',
    'rtable'      => 'STATUT_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
