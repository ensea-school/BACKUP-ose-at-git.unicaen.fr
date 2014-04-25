<?php

namespace Application\Provider\Role;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\ORM\EntityManager;
use UnicaenAuth\Acl\NamedRole;

/**
 * Fournisseur de tous les rôles utilisateurs existants dans l'application.
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RoleProvider implements ProviderInterface
{
    const ROLE_ID_INTERVENANT = 'Intervenant';
    
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * @var array
     */
    protected $config = array();
    
    /**
     * @var array
     */
    protected $roles;
    
    /**
     * 
     * @param EntityManager $entityManager
     * @param array $config
     */
    public function __construct(EntityManager $entityManager, $config = null)
    {
        $this->entityManager = $entityManager;
        $this->config        = $config;
    }
    
    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        if (null === $this->roles) {
            $roleIntervenant = new NamedRole(self::ROLE_ID_INTERVENANT, 'user');
            
            $this->roles = array();
//            $this->roles[] = $roleIntervenant;
        }
        
        return $this->roles;
    }
}