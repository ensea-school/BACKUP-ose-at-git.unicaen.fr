<?php

namespace Application\Acl;

use UnicaenAuth\Acl\NamedRole;
use Application\Entity\Db\TypeRole;
use Zend\Permissions\Acl\Resource;
use Application\Entity\Db\Privilege;

/**
 * Rôle père de tous les rôles "administrateur".
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends NamedRole
{

    const ROLE_ID = 'role';

    /**
     * Type de rôle
     *
     * @var TypeRole
     */
    protected $typeRole;



    public function __construct($id = self::ROLE_ID, $parent = 'user', $name = 'Rôle', $description = null, $selectable = true)
    {
        parent::__construct($id, $parent, $name, $description, $selectable);
    }

    /**
     *
     * @return TypeRole
     */
    function getTypeRole()
    {
        return $this->typeRole;
    }

    function setTypeRole(TypeRole $typeRole)
    {
        $this->typeRole = $typeRole;
        return $this;
    }

    /**
     *
     * @param Resource|string $resource
     * @param Privilege|string $privilege
     */
    function hasPrivilege( $resource, $privilege )
    {
        if ($typeRole = $this->getTypeRole()){
            return $typeRole->hasPrivilege($resource, $privilege);
        }
        return false;
    }
}