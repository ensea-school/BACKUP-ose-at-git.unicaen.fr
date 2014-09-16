<?php

namespace Application\Assertion;

use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\Service;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceAssertion extends AbstractAssertion
{

    /**
     * @var Service
     */
    protected $service;

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
        if ($resource instanceof Service) {
            return $this->assertEntity($acl, $role, $resource, $privilege);
        }

        return false;
    }

    /**
     *
     * @param Acl $acl
     * @param RoleInterface $role
     * @param ResourceInterface $resource
     * @param string $privilege
     * @return boolean
     */
    protected function assertEntity(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        if (!parent::assertCRUD($acl, $role, $resource, $privilege)) {
            return false;
        }

        $this->service = $resource;

        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
//        if ($this->getSelectedIdentityRole() instanceof ComposanteDbRole){
//            return true;
//        }

        return true;
    }
}