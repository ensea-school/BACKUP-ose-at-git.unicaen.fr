<?php

//@formatter:off

return [
    'name'        => 'FRVH_VOLUME_HORAIRE_FK',
    'table'       => 'FORMULE_RESULTAT_VH',
    'rtable'      => 'VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
