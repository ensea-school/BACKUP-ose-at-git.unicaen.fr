<?php

//@formatter:off

return [
    'name'        => 'INT_DOSSIER_PAYS_NAISS_FK',
    'table'       => 'INTERVENANT_DOSSIER',
    'rtable'      => 'PAYS',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'PAYS_NAISSANCE_ID' => 'ID',
    ],
];

//@formatter:on
