<?php
namespace Application\Provider\Identity;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Doctrine\ORM\EntityManager;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenApp\Service\EntityManagerAwareInterface;
use Application\Acl\IntervenantRole;
use Application\Acl\DbRole;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IdentityProvider implements ServiceLocatorAwareInterface, ChainableProvider, EntityManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use EntityManagerAwareTrait;
    
    /**
     * @var array
     */
    protected $roles;
    
    /**
     * Constructeur.
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->getEntityManager()->getFilters()->enable('historique');
    }
    
    /**
     * {@inheritDoc}
     */
    public function injectIdentityRoles(\UnicaenAuth\Provider\Identity\ChainEvent $event)
    {
        $event->addRoles($this->getIdentityRoles());
    }
    
    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        if (null === $this->roles) {
            $this->roles = array();
            
            if (!$this->getServiceLocator()->get('AuthUserContext')->getIdentity()) {
                return $this->roles;
            }
            
            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            $this->roles = array_merge($this->roles, $this->getDbRoles());
            
            /**
             * Tout le monde possède le rôle "intervenant"
             */
            $this->roles[] = new IntervenantRole();
        }
        
//        var_dump($this->roles);
        
        return $this->roles;
    }
    
    /**
     * Fetch dans la base de données les rôles que possède l'utilisateur sur une structure précise.
     * 
     * @return array Id des rôles trouvés
     */
    protected function getDbRoles()
    {
        $dbUser = $this->getServiceLocator()->get('AuthUserContext')->getDbUser(); /* @var $dbUser \Application\Entity\Db\Utilisateur */
        
        if (!$dbUser) {
            return array();
        }
        
        $roles = array();
        foreach ($dbUser->getPersonnel()->getRole() as $role) { /* @var $role \Application\Entity\Db\Role */
            $roles[] = new DbRole($role->getType(), $role->getStructure());
        }
        
        return $roles;
    }
}