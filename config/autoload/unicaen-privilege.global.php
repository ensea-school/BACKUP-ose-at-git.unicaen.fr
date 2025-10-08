<?php

use Application\Provider\Privileges;

return [
    'unicaen-auth' => [
        'privilege_entity_class' => \Utilisateur\Entity\Db\Privilege::class,
        'enable_privileges'      => true,
    ],
];