<?php

namespace Application\Acl;

use Application\Entity\Db\Interfaces\PersonnelAwareInterface;
use Application\Entity\Db\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "composante".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DrhRole extends Role implements PersonnelAwareInterface
{
    use PersonnelAwareTrait;

    const ROLE_ID = 'drh';

    public function __construct($id = self::ROLE_ID, $parent = Role::ROLE_ID, $name = 'DRH', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}
