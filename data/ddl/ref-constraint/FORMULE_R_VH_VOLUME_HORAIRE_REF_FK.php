<?php

//@formatter:off

return [
    'name'        => 'FORMULE_R_VH_VOLUME_HORAIRE_REF_FK',
    'table'       => 'FORMULE_RESULTAT_VOLUME_HORAIRE',
    'rtable'      => 'VOLUME_HORAIRE_REF',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_REF_ID' => 'ID',
    ],
];

//@formatter:on
