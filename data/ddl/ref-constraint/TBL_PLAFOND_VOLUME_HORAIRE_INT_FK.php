<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_VOLUME_HORAIRE_INT_FK',
    'table'       => 'TBL_PLAFOND_VOLUME_HORAIRE',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
