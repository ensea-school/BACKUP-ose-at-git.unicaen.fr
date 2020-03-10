<?php

//@formatter:off

return [
    'name'        => 'VOLUMES_HORAIRES_SERVICES_FK',
    'table'       => 'VOLUME_HORAIRE',
    'rtable'      => 'SERVICE',
    'delete_rule' => NULL,
    'index'       => NULL,
    'columns'     => [
        'SERVICE_ID' => 'ID',
    ],
];

//@formatter:on
