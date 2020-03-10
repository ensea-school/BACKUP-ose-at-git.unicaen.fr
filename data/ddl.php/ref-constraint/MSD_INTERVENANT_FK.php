<?php

//@formatter:off

return [
    'name'        => 'MSD_INTERVENANT_FK',
    'table'       => 'MODIFICATION_SERVICE_DU',
    'rtable'      => 'INTERVENANT',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INTERVENANT_ID' => 'ID',
    ],
];

//@formatter:on
