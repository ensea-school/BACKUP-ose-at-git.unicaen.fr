<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Interfaces\PersonnelAwareInterface;
use Application\Traits\PersonnelAwareTrait;

/**
 * Rôle père de tous les rôles "composante".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DrhRole extends NamedRole implements PersonnelAwareInterface
{
    use PersonnelAwareTrait;

    const ROLE_ID = 'drh';

    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'DRH', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}

class GestionnaireDrhRole extends DrhRole
{
    const ROLE_ID = 'gestionnaire-drh';

    public function __construct($id = self::ROLE_ID, $parent = DrhRole::ROLE_ID, $name = 'Gestionnaire DRH', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}

class ResponsableDrhRole extends DrhRole
{
    const ROLE_ID = 'responsable-drh';

    public function __construct($id = self::ROLE_ID, $parent = DrhRole::ROLE_ID, $name = 'Responsable DRH', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }
}