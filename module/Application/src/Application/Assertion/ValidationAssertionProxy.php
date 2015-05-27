<?php

namespace Application\Assertion;

use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Proxy pour les assertions concernant les validations.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationAssertionProxy extends AbstractAssertion
{
    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
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
        parent::assert($acl, $role, $resource, $privilege);
        
        return $this->getDelegate()->assert($acl, $role, $resource, $privilege);
    }
    
    /**
     * @return AbstractAssertion
     */
    protected function getDelegate()
    {
        switch ($this->resource->getTypeValidation()->getCode()) {
            case TypeValidationEntity::CODE_CLOTURE_REALISE:
                return $this->getServiceLocator()->get('ClotureRealiseAssertion');
            case TypeValidationEntity::CODE_ENSEIGNEMENT:
                return $this->getServiceLocator()->get('ValidationServiceAssertion');
            case TypeValidationEntity::CODE_REFERENTIEL:
                return $this->getServiceLocator()->get('ValidationReferentielAssertion');
            default:
                return null;
        }
    }
}