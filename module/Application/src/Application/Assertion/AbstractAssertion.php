<?php

namespace Application\Assertion;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Common\Exception\LogicException;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of AbstractAssertion
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractAssertion implements AssertionInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
 
    const PRIVILEGE_CREATE = 'create';
    const PRIVILEGE_READ   = 'read';
    const PRIVILEGE_UPDATE = 'update';
    const PRIVILEGE_DELETE = 'delete';
    
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
     * 
     * @return MvcEvent
     */
    protected function getMvcEvent()
    {
        return $this->getServiceLocator()->get('Application')->getMvcEvent();
    }


    /**
     * @return boolean
     */
    protected function assertCRUD(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        if (!$privilege) {
            return true;
        }
        
        switch ($privilege) {
            case self::PRIVILEGE_CREATE:
                return $this->_assertCreate($resource);
            case self::PRIVILEGE_READ:
                return $this->_assertRead($resource);
            case self::PRIVILEGE_UPDATE:
                return $this->_assertUpdate($resource);
            case self::PRIVILEGE_DELETE:
                return $this->_assertDelete($resource);
            default:
                throw new LogicException("Privilège spécifié inconnu: $privilege.");
        }
    }
    
    private function _assertCreate($resource)
    {
        if (is_object($resource) && $resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    private function _assertRead($resource)
    {
        if (is_object($resource) && !$resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    private function _assertUpdate($resource)
    {
        if (is_object($resource) && !$resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    private function _assertDelete($resource)
    {
        if (is_object($resource) && !$resource->getId()) {
            return false;
        }
        
        return true;
    }
    
    protected function getSelectedIdentityRole()
    {
        return $this->getContextProvider()->getSelectedIdentityRole();
    }
}