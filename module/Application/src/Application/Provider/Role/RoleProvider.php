<?php

namespace Application\Provider\Role;

use Application\Acl\AdministrateurRole;
use Application\Entity\Db\Role;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Interfaces\StructureAwareInterface;
use BjyAuthorize\Provider\Role\ProviderInterface;
use Exception;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la configuration du fournisseur
 *
 *  * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RoleProvider implements ProviderInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $roles;

    /**
     * Constructeur.
     * @param array $config
     */
    public function __construct( $config = [] )
    {
        $this->config = $config;
    }

    public function init()
    {
        $this->getEntityManager()->getFilters()->enable('historique');
    }

    /**
     * @return RoleInterface[]
     */
    public function getRoles()
    {
        if (null === $this->roles) {
            $this->roles = array();

            // Chargement des rôles de base
            foreach( $this->config as $classname ){
                if (class_exists( $classname )){
                    $role = new $classname; /* @var $role RoleInterface */
                    $this->roles[$role->getRoleId()] = $role;
                }else{
                    throw new LogicException('La classe "'.$classname.'" déclarée dans la configuration du fournisseur de rôles n\'a pas été trouvée.');
                }
            }

            // chargement des rôles métiers
            $qb = $this->getEntityManager()->createQueryBuilder()
                ->from("Application\Entity\Db\Role", "r")
                ->select("r, tr, s")
                ->distinct()
                ->join("r.type", "tr")
                ->leftJoin("r.structure", "s");
            foreach ($qb->getQuery()->getResult() as $role) { /* @var $role Role */
                $roleId = $role->getType()->getCode();
                if (! isset($this->roles[$roleId])){
                    throw new Exception('Le rôle "'.$roleId.'" est inconnu.');
                }
                $classname = get_class($this->roles[$roleId]);
                if ($this->roles[$roleId] instanceof StructureAwareInterface && $role->getStructure()){
                    $roleId .= '-'.$role->getStructure()->getSourceCode();
                    $this->roles[$roleId] = new $classname($roleId);
                    $this->roles[$roleId]->setStructure( $role->getStructure() );
                }else{
                    $this->roles[$roleId] = new $classname($roleId);
                }
                $this->roles[$roleId]->setTypeRole( $role->getType() );
                
                $this->injectSelectedStructureInRole($this->roles[$roleId]);
            }
        }
        
        return $this->roles;
    }

    /**
     * Inject la structure sélectionnée en session dans le rôle Administrateur.
     * 
     * @param Role $role
     * @return self
     */
    public function injectSelectedStructureInRole($role)
    {
        if (! $role instanceof AdministrateurRole) {
            return $this;
        }
            
        $role->setStructure($this->structureSelectionnee);
        
        return $this;
    }

    /**
     * @var StructureEntity
     */
    protected $structureSelectionnee;
    
    public function setStructureSelectionnee(StructureEntity $structureSelectionnee = null)
    {
        $this->structureSelectionnee = $structureSelectionnee;
        
        return $this;
    }
}
