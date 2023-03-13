<?php

//@formatter:off

return [
    'name'        => 'MISSION_ETUDIANT_INTERVENANT_FK',
    'table'       => 'MISSION_ETUDIANT',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
