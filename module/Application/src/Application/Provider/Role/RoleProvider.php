<?php

namespace Application\Provider\Role;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\DbRole;
use Application\Acl\ComposanteRole;
use Application\Entity\Db\Role as RoleEntity;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la table TYPE_ROLE ;
 * - rôle "intervenant".
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RoleProvider implements ProviderInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    
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
     * @param EntityManager $entityManager
     * @param array $config
     */
    public function __construct(EntityManager $entityManager, $config = null)
    {
        $this->setEntityManager($entityManager);
        $this->getEntityManager()->getFilters()->enable('historique');
        
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
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Role')->createQueryBuilder('r')
                    ->select('r, tr, s')
                    ->distinct()
                    ->innerJoin('r.type', 'tr')
                    ->innerJoin('r.structure', 's')
                    ->innerJoin('s.elementPedagogique', 'ep')
                    ->where('tr.code <> :code')->setParameter('code', 'IND')
                    ->andWhere('s.niveau = :niv')->setParameter('niv', 2);
            $rolesComposante = $qb->getQuery()->getResult();
            
            /**
             * Rôles "autres" : exercés sur une structure de niveau 2 NON PORTEUSE d'éléments pédagogiques
             */
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Role')->createQueryBuilder('r')
                    ->select('r, tr, s')
                    ->distinct()
                    ->innerJoin('r.type', 'tr')
                    ->innerJoin('r.structure', 's')
                    ->where('tr.code <> :code')->setParameter('code', 'IND')
                    ->andWhere('s.niveau = :niv')->setParameter('niv', 2)
                    ->andWhere('SIZE(s.elementPedagogique) = 0');
            $rolesAutres = $qb->getQuery()->getResult();
            
//            var_dump($qb->getQuery()->getSQL());
//            foreach ($dbRoles as $r) { /* @var $r \Application\Entity\Db\Role */
//                var_dump($r->getType() . "", "" . $r->getStructure() );
//            }
            
            /**
             * Collecte des rôles
             */
            $roles = array();
            $roles[$roleIntervenant->getRoleId()]          = $roleIntervenant;
            $roles[$roleIntervenantPermanent->getRoleId()] = $roleIntervenantPermanent;
            $roles[$roleIntervenantExterieur->getRoleId()] = $roleIntervenantExterieur;
            foreach ($rolesComposante as $r) { /* @var $r \Application\Entity\Db\Role */
                $role = new ComposanteRole($r->getType(), $r->getStructure(), null);
                $roles[$role->getRoleId()] = $role;
            }
            foreach ($rolesAutres as $r) { /* @var $r \Application\Entity\Db\Role */
                $role = new DbRole($r->getType(), $r->getStructure(), null);
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
        
        return new \Zend\Permissions\Acl\Role\GenericRole($roleId);
    }
}