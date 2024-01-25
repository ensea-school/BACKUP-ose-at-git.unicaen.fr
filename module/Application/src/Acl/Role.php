<?php

namespace Application\Acl;

use Application\Entity\Db\Privilege;
use Application\Entity\Db\Traits\PerimetreAwareTrait;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Lieu\Entity\Db\StructureAwareTrait;
use UnicaenUtilisateur\Acl\NamedRole;

/**
 * Rôle
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends NamedRole
{
    use StructureAwareTrait;
    use IntervenantAwareTrait;
    use PerimetreAwareTrait;

    protected $peutChangerStructure;

    /**
     * @var string[]
     */
    protected $privileges = [];

    /**
     * @var \Application\Entity\Db\Role
     */
    protected $dbRole;

    const ROLE_ID = 'role';



    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Rôle inconnu', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }



    /**
     * @return \Application\Entity\Db\Role
     */
    public function getDbRole()
    {
        return $this->dbRole;
    }



    /**
     * @param \Application\Entity\Db\Role $dbRole
     *
     * @return Role
     */
    public function setDbRole($dbRole)
    {
        $this->dbRole = $dbRole;

        return $this;
    }



    public function initPrivileges(array $privileges)
    {
        $this->privileges = $privileges;
    }



    public function getPrivileges()
    {
        return $this->privileges;
    }



    public function hasPrivilege($privilege)
    {
        if ($privilege instanceof Privilege) {
            $privilege = $privilege->getFullCode();
        }

        return in_array($privilege, $this->privileges);
    }



    /**
     * @return mixed
     */
    public function getPeutChangerStructure()
    {
        return $this->peutChangerStructure;
    }



    /**
     * @param mixed $peutChangerStructure
     *
     * @return Role
     */
    public function setPeutChangerStructure($peutChangerStructure)
    {
        $this->peutChangerStructure = $peutChangerStructure;

        return $this;
    }

}