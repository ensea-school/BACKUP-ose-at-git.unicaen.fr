<?php

namespace Application\Provider\Role;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\DbRole;
use Application\Acl\ComposanteRole;
use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\Role as RoleEntity;
use Application\Service\Role as RoleService;
use Application\Service\RoleUtilisateur as RoleUtilisateurService;
use Zend\Permissions\Acl\Role\GenericRole;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la table TYPE_ROLE ;
 * - rôle "intervenant".
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RoleProvider implements ProviderInterface
{
    const ROLE_ID_ADMIN = 'Administrateur';
    
    /**
     * @var array
     */
    protected $config = array();
    
    /**
     * @var array
     */
    protected $roles;
    
    /**
     * Constructeur.
     * 
     * @param RoleService $serviceRole
     * @param RoleUtilisateurService $serviceRoleUtilisateur
     * @param array $config
     */
    public function __construct(
            RoleService $serviceRole, 
            RoleUtilisateurService $serviceRoleUtilisateur, 
            $config = null)
    {
        $this
                ->setServiceRole($serviceRole)
                ->setServiceRoleUtilisateur($serviceRoleUtilisateur);
        
        $this->config = $config;
    }
    
    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     * @see \Application\Entity\Db\TypeRole
     */
    public function getRoles()
    {
        if (null === $this->roles) {            
            /**
             * Rôles "intervenant"
             */
            $roleIntervenant          = new IntervenantRole();
            $roleIntervenantPermanent = new IntervenantPermanentRole();
            $roleIntervenantExterieur = new IntervenantExterieurRole();
            
            /**
             * Rôles "composante" : exercés sur une structure de niveau 2 PORTEUSE d'éléments pédagogiques
             */
            // rôle père
            $roleComposante = new ComposanteRole();
            // rôles métier (importés d'Harpege) correspondant au ROLE_ID PHP
            $qb = $this->serviceRole->finderRolePersonnelByRole($roleComposante->getRoleId());
            $rolesComposante = array();
            foreach ($qb->getQuery()->getResult() as $vrp) { /* @var $vrp \Application\Entity\Db\VRolePersonnel */
                $rolesComposante[] = $vrp->getRole();
            }

            /**
             * Rôles utilisateurs au sein de l'application (tables UTILISATEUR, ROLE_UTILISATEUR et ROLE_UTILISATEUR_LINKER)
             */
            $rolesAppli = $this->serviceRoleUtilisateur->getList();
            
            /**
             * Collecte des rôles
             */
            $roles = array();
            $roles[$roleIntervenant->getRoleId()]          = $roleIntervenant;
            $roles[$roleIntervenantPermanent->getRoleId()] = $roleIntervenantPermanent;
            $roles[$roleIntervenantExterieur->getRoleId()] = $roleIntervenantExterieur;
            $roles[$roleComposante->getRoleId()]           = $roleComposante;
            foreach ($rolesComposante as $r) { /* @var $r \Application\Entity\Db\Role */
                $role = new ComposanteDbRole($r->getType(), $r->getStructure(), $roleComposante);
                $roles[$role->getRoleId()] = $role;
            }
            foreach ($rolesAppli as $r) { /* @var $r \Application\Entity\Db\RoleUtilisateur */
                $role = new \UnicaenAuth\Acl\NamedRole($r->getRoleId());
                $roles[$role->getRoleId()] = $role;
            }
            
            $this->roles = $roles;
        }
        
//        var_dump(array_keys($this->roles));
        
        return $this->roles;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Role $roleEntity
     * @return \Zend\Permissions\Acl\Role\GenericRole
     */
    public function getRoleFromRoleEntity(RoleEntity $roleEntity)
    {
        $roles  = $this->getRoles();
        $roleId = DbRole::createRoleId($roleEntity->getType(), $roleEntity->getStructure());
        
        if (isset($roles[$roleId])) {
            return $roles[$roleId];
        }
        
        return new GenericRole($roleId);
    }
    
    /**
     * @var RoleService
     */
    private $serviceRole;
    
    /**
     * @var RoleUtilisateurService
     */
    private $serviceRoleUtilisateur;
    
    public function setServiceRole(RoleService $serviceRole)
    {
        $this->serviceRole = $serviceRole;
        return $this;
    }

    public function setServiceRoleUtilisateur(RoleUtilisateurService $serviceRoleUtilisateur)
    {
        $this->serviceRoleUtilisateur = $serviceRoleUtilisateur;
        return $this;
    }
}