<?php

namespace Application\Service;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of OffreFormationAssertion
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class OffreFormationAssertion extends AbstractService implements AssertionInterface
{
    /**
     * 
     * 
     * @return array
     */
    public function __sleep()
    {
        return array();
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
//        $event   = $this->getServiceLocator()->get('application')->getMvcEvent(); /* @var $event \Zend\Mvc\MvcEvent */
//        $request = $event->getRequest(); /* @var $request \Zend\Http\Request */
//        $role    = $this->getContextProvider()->getSelectedIdentityRole();
//        
//        $match      = $event->getRouteMatch();
//        $controller = $match->getParam('controller');
//        $action     = $match->getParam('action');
//        $request    = $event->getRequest();
        
//        var_dump($event->getName(), $controller, $action);

        return true;
    }
}