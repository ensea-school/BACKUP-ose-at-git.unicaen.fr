<?php

//@formatter:off

return [
    'name'        => 'HSM_TYPE_VOLUME_HORAIRE_FK',
    'table'       => 'HISTO_INTERVENANT_SERVICE',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
