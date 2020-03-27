<?php

//@formatter:off

return [
    'name'        => 'FRSR_SERVICE_REFERENTIEL_FK',
    'table'       => 'FORMULE_RESULTAT_SERVICE_REF',
    'rtable'      => 'SERVICE_REFERENTIEL',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'SERVICE_REFERENTIEL_ID' => 'ID',
    ],
];

//@formatter:on
