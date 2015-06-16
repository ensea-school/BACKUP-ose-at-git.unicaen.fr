<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Entity\Db\Role as DbRole;

/**
 * Rôle père de tous les rôles "administrateur".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends NamedRole
{
    use \Application\Traits\StructureAwareTrait,
        \Application\Traits\PersonnelAwareTrait,
        \Application\Traits\IntervenantAwareTrait;

    const ROLE_ID = 'role';

    /**
     * Rôle en BDD
     *
     * @var DbRole
     */
    protected $dbRole;



    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Rôle', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

}