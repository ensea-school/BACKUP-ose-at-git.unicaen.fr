<?php

//@formatter:off

return [
    'name'        => 'HSM_UTILISATEUR_FK',
    'table'       => 'HISTO_INTERVENANT_SERVICE',
    'rtable'      => 'UTILISATEUR',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'HISTO_MODIFICATEUR_ID' => 'ID',
    ],
];

//@formatter:on
