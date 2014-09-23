<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "administrateur".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AdministrateurRole extends Role implements PersonnelAwareInterface
{
    use PersonnelAwareTrait;

    const ROLE_ID = 'administrateur';

    public function __construct($id = self::ROLE_ID, $parent = Role::ROLE_ID, $name = 'Administrateur', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}