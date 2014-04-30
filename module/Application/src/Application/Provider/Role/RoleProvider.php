<?php

namespace Application\Provider\Role;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Acl\IntervenantRole;
use Application\Acl\DbRole;

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
             * Rôle de base "intervenant"
             */
            $roleIntervenant = new IntervenantRole();
            
            /**
             * Rôles exercés sur une structure de niveau 2 porteuse d'éléments pédagogiques
             */
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Role')->createQueryBuilder('r')
                    ->select('r, tr, s')
                    ->distinct()
                    ->innerJoin('r.type', 'tr')
                    ->innerJoin('r.structure', 's')
                    ->innerJoin('s.elementPedagogique', 'ep')
                    ->where('tr.code <> :code')->setParameter('code', 'IND')
                    ->andWhere('s.niveau = :niv')->setParameter('niv', 2);
            $dbRoles = $qb->getQuery()->getResult();

            /**
             * Collecte des rôles
             */
            $this->roles = array();
            $this->roles[$roleIntervenant->getRoleId()] = $roleIntervenant;
            foreach ($dbRoles as $r) { /* @var $r \Application\Entity\Db\Role */
                $role = new DbRole($r->getType(), $r->getStructure(), null);
                $this->roles[$role->getRoleId()] = $role;
            }
        }
        
//        foreach ($this->roles as $r) { /* @var $r \Zend\Permissions\Acl\Role\RoleInterface */
//            var_dump($r->getRoleId());
//        }
        
        return $this->roles;
    }
}