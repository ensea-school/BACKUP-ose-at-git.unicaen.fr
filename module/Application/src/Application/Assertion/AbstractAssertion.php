<?php

namespace Application\Assertion;

use Application\Service\Traits\ContextAwareTrait;
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
abstract class AbstractAssertion implements AssertionInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;

    const PRIVILEGE_CREATE = 'create';
    const PRIVILEGE_READ   = 'read';
    const PRIVILEGE_UPDATE = 'update';
    const PRIVILEGE_DELETE = 'delete';
    
    /**
     * @var Acl
     */
    protected $acl;

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
     * @param  Acl                  $acl
     * @param  RoleInterface        $role
     * @param  ResourceInterface    $resource
     * @param  string               $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        $this->acl       = $acl;
        $this->resource  = $resource;
        $this->privilege = $privilege;
        $this->role      = $role;

        // gestion des privilèges
        if ($this->detectPrivilege($resource)){
            if (! $this->assertPrivilege ($acl, $role, ltrim( strstr( $resource, '/' ), '/'), $privilege)) return false;

        // gestion des contrôleurs
        }else if($this->detectController($resource)){
            $resource = (string)$resource;
            $spos = strpos($resource,'/')+1;
            $dpos = strrpos($resource, ':')+1;
            $controller = substr( $resource, $spos, $dpos-$spos-1);
            $action = substr( $resource, $dpos );
            if (! $this->assertController ($acl, $role, $controller, $action, $privilege)) return false;

        // gestion des entités
        }else if($this->detectEntity($resource)){
            if (! $this->assertEntity ($acl, $role, $resource, $privilege)) return false;

        // gestion de tout le reste
        }else{
            if (! $this->assertOther ($acl, $role, $resource, $privilege)) return false;

        }

        return true;
    }


    /**
     *
     * @param string $resource
     * @return boolean
     */
    private function detectPrivilege( $resource=null )
    {
        return is_string($resource) && 0 === strpos($resource, 'privilege/');
    }

    /**
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param string $privilege
     * @param string $subPrivilege
     * @return boolean
     */
    protected function assertPrivilege(Acl $acl, RoleInterface $role=null, $privilege=null, $subPrivilege=null)
    {
        return true;
    }


    /**
     *
     * @param string $resource
     * @return boolean
     */
    private function detectController( $resource=null )
    {
        return 0 === strpos($resource, 'controller/');
    }

    /**
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param string $controller
     * @param string $action
     * @param string $privilege
     * @return boolean
     */
    protected function assertController(Acl $acl, RoleInterface $role=null, $controller=null, $action=null, $privilege=null)
    {
        return true;
    }


    /**
     *
     * @param string $resource
     * @return boolean
     */
    private function detectEntity( $resource=null )
    {
        return
            is_object($resource)
            && method_exists($resource, 'getId');
    }

    /**
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param ResourceInterface $entity
     * @param string $privilege
     * @return boolean
     */
    protected function assertEntity(Acl $acl, RoleInterface $role=null, ResourceInterface $entity=null, $privilege=null)
    {
        return true;
    }


    /**
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param ResourceInterface $entity
     * @param string $privilege
     * @return boolean
     */
    protected function assertOther(Acl $acl, RoleInterface $role=null, ResourceInterface $entity=null, $privilege=null)
    {
        return true;
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
                return ! (is_object($this->resource) && $this->resource->getId());
            case self::PRIVILEGE_READ:
                return ! (is_object($this->resource) && !$this->resource->getId());
            case self::PRIVILEGE_UPDATE:
                return ! (is_object($this->resource) && !$this->resource->getId());
            case self::PRIVILEGE_DELETE:
                return ! (is_object($this->resource) && !$this->resource->getId());
            default:
                return true;
        }
    }


    /**
     * 
     * @return MvcEvent
     */
    protected function getMvcEvent()
    {
        $application = $this->getServiceLocator()->get('Application');
        return $application->getMvcEvent();
    }


    /**
     * @deprecated
     *
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
}