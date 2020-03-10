<?php

//@formatter:off

return [
    'name'        => 'FRVHR_VOLUME_HORAIRE_REF_FK',
    'table'       => 'FORMULE_RESULTAT_VH_REF',
    'rtable'      => 'VOLUME_HORAIRE_REF',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_REF_ID' => 'ID',
    ],
];

//@formatter:on
