<?php

//@formatter:off

return [
    'name'        => 'ETAT_SORTIE_SCFK',
    'table'       => 'ETAT_SORTIE',
    'rtable'      => 'UNICAEN_SIGNATURE_SIGNATUREFLOW',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SIGNATURE_CIRCUIT_ID' => 'ID',
    ],
];

//@formatter:on
