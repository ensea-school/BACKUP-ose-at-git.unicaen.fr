<?php

namespace Application\Acl;

use Zend\Permissions\Acl\Role\RoleInterface;
use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Application\Entity\Db\TypeRole;
use Application\Entity\Db\Structure;

class DbRole implements RoleInterface, HierarchicalRoleInterface
{    
    /**
     * @var TypeRole
     */
    protected $typeRole;
    
    /**
     * @var Structure
     */
    protected $structure;
    
    /**
     * @var string
     */
    protected $roleId;
    
    /**
     * @var string|RoleInterface|null
     */
    protected $parent;
    
    /**
     * Constructeur.
     * 
     * @param TypeRole $typeRole
     * @param Structure $structure
     * @param string|RoleInterface|null $parent
     */
    public function __construct(TypeRole $typeRole, Structure $structure, $parent = null)
    {
        $this->typeRole  = $typeRole;
        $this->structure = $structure;
        $this->parent    = $parent;
    }
    
    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s (%s)", $this->getTypeRole(), $this->getStructure());
    }
    
    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        if (null === $this->roleId) {
            $this->roleId = static::createRoleId($this->getTypeRole(), $this->getStructure());
        }
        return $this->roleId;
    }
    
    /**
     * Fabrique un id de rôle au format utilisé par cette classe de rôle.
     * 
     * @param \Application\Entity\Db\TypeRole $typeRole
     * @param \Application\Entity\Db\Structure $structure
     * @return string
     */
    static public function createRoleId(TypeRole $typeRole, Structure $structure)
    {
        return sprintf("%s_%s", $typeRole->getCode(), $structure->getSourceCode());
    }
    
    /**
     * Get the parent role
     *
     * @return string|RoleInterface|null
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * 
     * @return TypeRole
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }

    /**
     * 
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * 
     * @param \Application\Entity\Db\TypeRole $typeRole
     * @return \Application\Provider\Role\DbRole
     */
    public function setTypeRole(TypeRole $typeRole)
    {
        $this->typeRole = $typeRole;
        return $this;
    }

    /**
     * 
     * @param \Application\Entity\Db\Structure $structure
     * @return \Application\Provider\Role\DbRole
     */
    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;
        return $this;
    }
}