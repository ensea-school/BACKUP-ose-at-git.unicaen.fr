<?php

//@formatter:off

return [
    'name'        => 'FORMULE_R_INT_ETAT_VOLUME_HORAIRE_FK',
    'table'       => 'FORMULE_RESULTAT_INTERVENANT',
    'rtable'      => 'ETAT_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAT_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
