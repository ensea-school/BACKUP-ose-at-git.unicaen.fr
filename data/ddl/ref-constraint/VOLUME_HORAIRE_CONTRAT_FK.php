<?php

//@formatter:off

return [
    'name'        => 'VOLUME_HORAIRE_CONTRAT_FK',
    'table'       => 'VOLUME_HORAIRE',
    'rtable'      => 'CONTRAT',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'CONTRAT_ID' => 'ID',
    ],
];

//@formatter:on
