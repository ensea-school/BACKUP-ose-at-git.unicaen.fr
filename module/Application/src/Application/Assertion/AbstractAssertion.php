<?php

namespace Application\Assertion;

use DateTime;
use Application\Acl\IntervenantPermanentRole;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Acl\Role;

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
     * @var Acl
     */
    protected $acl;

    /**
     * copntrôle par les privileges activés ou non
     *
     * @var boolean
     */
    protected $assertPrivilegesEnabled = false;

    /**
     * contrôle par les ressources activés ou non
     *
     * @var boolean
     */
    protected $assertResourcesEnabled = true;

    /**
     * @var string
     */
    protected $privilege;

    /**
     * @var ResourceInterface|string
     */
    protected $resource;

    /**
     * @var RoleInterface
     */
    protected $role;

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
     * $role, $this->resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl               $acl
     * @param  RoleInterface     $role
     * @param  ResourceInterface $resource
     * @param  string            $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        $this->acl       = $acl;
        $this->resource  = $resource;
        $this->privilege = $privilege;
        $this->role      = $this->getSelectedIdentityRole();

        if (! $this->assertPrivilege()                              ) return false;
        if (! $this->assertResource()                               ) return false;
        return true;
    }

    private function assertPrivilege()
    {
        if (! $this->assertPrivilegesEnabled) return true; // si pas activé alors on sort
        if ($this->role instanceof Role && ! empty($this->resource) && ! empty($this->privilege)){
            return $this->role->hasPrivilege($this->privilege, $this->resource);
        }
        return true;
    }

    private function assertResource()
    {
        if (! $this->assertResourcesEnabled) return true; // si pas activé alors on sort
        if (! $this->resource instanceof ResourceInterface) return true; // pas assez de précisions
        $resourceId = $this->resource->getResourceId();

        if (method_exists( $this, 'assertResource'.$resourceId)){
            return $this->{'assertResource'.$resourceId}( $this->resource );
        }

        return true;
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
    protected function assertCRUD()
    {
        if (!$this->privilege) {
            return true;
        }
        
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
                return true;
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
    
    protected function getSelectedIdentityRole()
    {
        return $this->getContextProvider()->getSelectedIdentityRole();
    }
    
    /**
     * Retourne un privilège "normalisé" en fonction du type de ressource spécifié.
     * 
     * - Si la ressource est un objet, le privilège est directement utilisable.
     * - Sinon la ressource est sans doute de la forme "controller/Application\Controller\MonController:monAction"
     * (module BjyAuthorize) et le privilège sera le nom de l'action.
     * 
     * @param string $privilege
     * @param string|object $resource Ex: "Application\Controller\MonController:monAction"
     * @return string
     */
    protected function normalizedPrivilege($privilege, $resource)
    {
        if (is_object($resource)) {
            return $privilege;
        }
        
        if (!$privilege) {
            $privilege = ($tmp = strrchr($resource, $c = ':')) ? ltrim($tmp, $c) : null;
        }
        
        return $privilege;
    }

    /**
     * Teste si la date de fin de "privilège" du rôle courant est dépassée ou non.
     * 
     * @return boolean
     */
    protected function isDateFinPrivilegeDepassee()
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $dateFin = null;
        
        /**
         * Rôle Intervenant Permanent
         */
        if ($this->role instanceof IntervenantPermanentRole) {
            // il existe une date de fin de saisie (i.e. ajout, modif, suppression) de service par les intervenants permanents eux-mêmes
            if (in_array($this->privilege, [self::PRIVILEGE_CREATE, self::PRIVILEGE_UPDATE, self::PRIVILEGE_DELETE])) {
                $dateFin = $context->getDateFinSaisiePermanents();
                
                /**
                 * Vilaine verrue pour prolonger la période de saisie des permanents de l'ESPE
                 * @todo Virer cette verrue après le 27/03/2015 !!
                 */
                if ($this->role->getIntervenant()->getStructure()->getSourceCode() === 'E01') {
                    $dateFin = new \DateTime('2015-03-27');
                }
            }
        }

        if (null === $dateFin) {
            return false;
        }
                
        $now = new DateTime();

        $now->setTime(0, 0, 0);
        $dateFin->setTime(0, 0, 0);

        return $now > $dateFin;
    }

    public static function getAssertionId()
    {
        $getCalledClass = get_called_class();
        $getCalledClass = substr( $getCalledClass, strrpos( $getCalledClass, '\\')+1 );
        return $getCalledClass;
    }
}