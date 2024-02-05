<?php

//@formatter:off

return [
    'name'        => 'FORMULE_R_VH_INTERVENANT_FK',
    'table'       => 'FORMULE_RESULTAT_VOLUME_HORAIRE',
    'rtable'      => 'FORMULE_RESULTAT_INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'FORMULE_RESULTAT_INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
