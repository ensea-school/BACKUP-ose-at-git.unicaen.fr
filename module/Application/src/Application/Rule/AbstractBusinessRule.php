<?php

namespace Application\Rule;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Traits\MessageAwareTrait;;
use Common\Exception\LogicException;
use Application\Entity\Db\Structure;
use Application\Acl\Role;

/**
 * Description of AbstractBusinessRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractBusinessRule implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use MessageAwareTrait;

    /**
     * Rôle.
     * 
     * @var Role
     */
    protected $role;

    /**
     * Privilège.
     * 
     * @var string
     */
    protected $privilege;

    /**
     * Structure correspondant au rôle courant.
     * 
     * @var Structure
     */
    protected $structureRole;

    /**
     * Exécute la règle.
     * 
     * @return self
     * @throws LogicException
     */
    public function execute()
    {
        if (! $this->role) {
            throw new LogicException("Un rôle doit être spécifié.");
        }
        
        $this->determineStructureRole();
        
        return $this;
    }

    /**
     * Détermine la structure associée au rôle utilisateur courant.
     * 
     * @return self
     */
    abstract protected function determineStructureRole();
    
    /**
     * Détermine si le rôle courant possède le privilège spécifié.
     * 
     * @param string $privilege Ex: 'create', 'read'
     * @return boolean
     */
    abstract public function isAllowed($privilege);

    /**
     * 
     * @param Role $role
     * @return self
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * 
     * @param string $privilege
     * @return self
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
        return $this;
    }
}