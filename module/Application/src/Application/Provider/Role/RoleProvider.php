<?php

namespace Application\Provider\Role;

use Application\Acl\AdministrateurRole;
use Application\Entity\Db\Affectation;
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
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

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
            $this->roles = $this->makeRoles();
        }
        return $this->roles;
    }

    protected function makeRoles()
    {
        $roles = [];

        // Chargement des rôles de base
        foreach( $this->config as $classname ){
            if (class_exists( $classname )){
                $role = new $classname; /* @var $role RoleInterface */
                $roles[$role->getRoleId()] = $role;
            }else{
                throw new LogicException('La classe "'.$classname.'" déclarée dans la configuration du fournisseur de rôles n\'a pas été trouvée.');
            }
        }
        if (($utilisateur = $this->getUtilisateur()) && ($personnel = $utilisateur->getPersonnel())){
            // chargement des rôles métiers
            $qb = $this->getEntityManager()->createQueryBuilder()
                ->from("Application\Entity\Db\Affectation", "a")
                ->select("a, r, s")
                ->distinct()
                ->join("a.role", "r")
                ->leftJoin("a.structure", "s")
                ->andWhere('1=compriseEntre(a.histoCreation,a.histoDestruction)')
                ->andWhere('1=compriseEntre(r.histoCreation,r.histoDestruction)')
                ->andWhere("a.personnel = :personnel")->setParameter(':personnel', $personnel);
            foreach ($qb->getQuery()->getResult() as $affectation) { /* @var $affectation Affectation */
                $roleId = $affectation->getRole()->getCode();
                if (! isset($roles[$roleId])){
                    throw new Exception('Le rôle "'.$roleId.'" est inconnu.');
                }
                $classname = get_class($roles[$roleId]);
                if ($roles[$roleId] instanceof StructureAwareInterface && $affectation->getStructure()){
                    $roleId .= '-'.$affectation->getStructure()->getSourceCode();
                    $roles[$roleId] = new $classname($roleId);
                    $roles[$roleId]->setStructure( $affectation->getStructure() );
                }else{
                    $roles[$roleId] = new $classname($roleId);
                }
                $roles[$roleId]->setDbRole( $affectation->getRole() );

                $this->injectSelectedStructureInRole($roles[$roleId]);
            }
        }
        return $roles;
    }

    /**
     *
     * @return \Application\Entity\Db\Utilisateur
     */
    public function getUtilisateur()
    {
        $identity = $this->getServiceLocator()->get('AuthUserContext')->getIdentity();
        if (isset($identity['db'])){
            return $identity['db'];
        }else{
            return null;
        }
    }

    /**
     * Inject la structure sélectionnée en session dans le rôle Administrateur.
     * 
     * @param \Application\Acl\Role $role
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
