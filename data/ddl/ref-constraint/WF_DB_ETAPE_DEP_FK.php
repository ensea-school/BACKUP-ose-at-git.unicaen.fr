<?php

//@formatter:off

return [
    'name'        => 'WF_DB_ETAPE_DEP_FK',
    'table'       => 'WF_DEP_BLOQUANTE',
    'rtable'      => 'WF_ETAPE_DEP',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'WF_ETAPE_DEP_ID' => 'ID',
    ],
];

//@formatter:on
