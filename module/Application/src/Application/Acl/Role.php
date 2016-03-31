<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\PersonnelAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\PerimetreAwareTrait;
use UnicaenAuth\Entity\Db\Privilege;

/**
 * Rôle
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends NamedRole
{
    use StructureAwareTrait;
    use PersonnelAwareTrait;
    use IntervenantAwareTrait;
    use PerimetreAwareTrait;

    protected $peutChangerStructure;

    /**
     * @var string[]
     */
    protected $privileges = [];



    const ROLE_ID = 'role';



    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Rôle inconnu', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }



    public function initPrivileges( array $privileges )
    {
        $this->privileges = $privileges;
    }



    public function getPrivileges()
    {
        return $this->privileges;
    }



    public function hasPrivilege( $privilege )
    {
        if ($privilege instanceof Privilege){
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