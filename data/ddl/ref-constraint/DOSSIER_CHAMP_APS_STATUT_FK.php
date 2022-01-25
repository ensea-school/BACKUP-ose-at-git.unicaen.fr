<?php

//@formatter:off

return [
    'name'        => 'DOSSIER_CHAMP_APS_STATUT_FK',
    'table'       => 'DOSSIER_CHAMP_AUTRE_PAR_STATUT',
    'rtable'      => 'STATUT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'STATUT_ID' => 'ID',
    ],
];

//@formatter:on
