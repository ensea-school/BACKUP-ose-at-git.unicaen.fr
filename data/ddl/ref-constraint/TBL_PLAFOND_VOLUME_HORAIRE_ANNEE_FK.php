<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_VOLUME_HORAIRE_ANNEE_FK',
    'table'       => 'TBL_PLAFOND_VOLUME_HORAIRE',
    'rtable'      => 'ANNEE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ANNEE_ID' => 'ID',
    ],
];

//@formatter:on
