<?php

//@formatter:off

return [
    'name'        => 'FTI_ETAT_VOLUME_HORAIRE_FK',
    'table'       => 'FORMULE_TEST_INTERVENANT',
    'rtable'      => 'ETAT_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ETAT_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
