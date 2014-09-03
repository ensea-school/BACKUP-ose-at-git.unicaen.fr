<?php
namespace Application\Provider\Identity;

use Application\Acl\DbRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\Role;
use Application\Entity\Db\Utilisateur;
use Common\Exception\RuntimeException;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Identity\ChainableProvider;
use UnicaenAuth\Provider\Identity\ChainEvent;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

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
    public function injectIdentityRoles(ChainEvent $event)
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
             * Rôle correspondant au type d'intervenant auquel appartient l'utilisateur
             */
            $this->roles[] = $this->getIntervenantRole();
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
        $utilisateur = $this->getDbUser();
        $roles       = array();
        
        if (!$utilisateur) {
            return $roles;
        }
        
        /**
         * Rôles utilisateurs au sein de l'application (tables UTILISATEUR, ROLE_UTILISATEUR et ROLE_UTILISATEUR_LINKER)
         */
        foreach ($utilisateur->getRoleUtilisateur() as $roleUtilisateur) { /* @var $roleUtilisateur \Application\Entity\Db\RoleUtilisateur */
            $roles[] = $roleUtilisateur->getRoleId();
        }

        /**
         * Responsabilités métier importées (tables ROLE et TYPE_ROLE)
         */
        if ($utilisateur->getPersonnel()) {
            foreach ($utilisateur->getPersonnel()->getRole() as $role) { /* @var $role Role */
                $roles[] = DbRole::createRoleId($role->getType(), $role->getStructure()); // le role id suffit, pas besoin d'instance
            }
        }
        
        return $roles;
    }
    
    /**
     * Retourne le rôle correspondant au type d'intervenant auquel appartient l'utilisateur.
     * 
     * @return RoleInterface
     */
    protected function getIntervenantRole()
    {
        $utilisateur = $this->getDbUser();
        
        if (!$utilisateur) {
            return null;
        }
        
        $intervenant = $utilisateur->getIntervenant();
        
        if (!$intervenant) {
            return IntervenantRole::ROLE_ID;
        }

        $statut = $intervenant->getStatut()->getSourceCode();
        if ($statut === \Application\Entity\Db\StatutIntervenant::NON_AUTORISE){
            return null;
        }

        if ($intervenant instanceof IntervenantPermanent) {
            $role = IntervenantPermanentRole::ROLE_ID;
        }
        elseif ($intervenant instanceof IntervenantExterieur) {
            $role = IntervenantExterieurRole::ROLE_ID;
        }
        else {
            throw new RuntimeException("Type d'intervenant inattendu : " . get_class($intervenant));
        }
        
        return $role;
    }
    
    /**
     * Retourne l'utilisateur connecté.
     * 
     * @return Utilisateur
     */
    private function getDbUser()
    {
        return $this->getServiceLocator()->get('AuthUserContext')->getDbUser(); /* @var $dbUser Utilisateur */
    }
}