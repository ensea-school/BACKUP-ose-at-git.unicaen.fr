<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "foad".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FoadRole extends NamedRole implements PersonnelAwareInterface
{
    use PersonnelAwareTrait;

    const ROLE_ID = 'foad';

    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Foad', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

}

class ResponsableFoadRole extends FoadRole
{
    const ROLE_ID = 'responsable-foad';

    public function __construct($id = self::ROLE_ID, $parent = FoadRole::ROLE_ID, $name = 'Responsable FOAD', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}