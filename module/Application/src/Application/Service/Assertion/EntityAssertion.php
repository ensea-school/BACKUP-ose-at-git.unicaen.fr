<?php

namespace Application\Service\Assertion;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of EntityAssertion
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class EntityAssertion implements AssertionInterface, ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
 
    const PRIVILEGE_CREATE = 'create';
    const PRIVILEGE_READ   = 'read';
    const PRIVILEGE_UPDATE = 'update';
    const PRIVILEGE_DELETE = 'delete';
    
    protected $acl;
    protected $role;
    protected $resource;
    protected $privilege;
    protected $identityRole;
    
    /**
     * !!!! Pour éviter l'erreur "Serialization of 'Closure' is not allowed"... !!!!
     * 
     * @return array
     */
    public function __sleep()
    {
        return [];
    }
    
    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl                        $acl
     * @param  RoleInterface         $role
     * @param  ResourceInterface $resource
     * @param  string                         $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        $this->acl       = $acl;
        $this->role      = $role;
        $this->resource  = $resource;
        $this->privilege = $privilege;
        
        $this->identityRole = $this->getContextProvider()->getSelectedIdentityRole();

        if (!$this->_assertCRUD()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @return boolean
     */
    private function _assertCRUD()
    {
        switch ($this->privilege) {
            case self::PRIVILEGE_CREATE:
                return $this->_assertCreate();
            case self::PRIVILEGE_READ:
                return $this->_assertRead();
            case self::PRIVILEGE_UPDATE:
                return $this->_assertUpdate();
            case self::PRIVILEGE_DELETE:
                return $this->_assertDelete();
            default:
                throw new \Common\Exception\LogicException("Privilège spécifié inconnu: $this->privilege.");
        }
    }
    
    private function _assertCreate()
    {
        if (is_object($this->resource) && $this->resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    private function _assertRead()
    {
        if (is_object($this->resource) && !$this->resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    private function _assertUpdate()
    {
        if (is_object($this->resource) && !$this->resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    private function _assertDelete()
    {
        if (is_object($this->resource) && !$this->resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @return boolean
     */
    protected function isCreate()
    {
        return self::PRIVILEGE_CREATE === $this->privilege;
    }
    
    /**
     * @return boolean
     */
    protected function isRead()
    {
        return self::PRIVILEGE_READ === $this->privilege;
    }
    
    /**
     * @return boolean
     */
    protected function isUpdate()
    {
        return self::PRIVILEGE_UPDATE === $this->privilege;
    }
    
    /**
     * @return boolean
     */
    protected function isDelete()
    {
        return self::PRIVILEGE_DELETE === $this->privilege;
    }
}