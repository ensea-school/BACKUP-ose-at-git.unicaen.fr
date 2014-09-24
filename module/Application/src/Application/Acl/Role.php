<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;

/**
 * Rôle père de tous les rôles "administrateur".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends NamedRole
{

    const ROLE_ID = 'role';

    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Rôle', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}