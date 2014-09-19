<?php

namespace Application\Provider\Role;

use BjyAuthorize\Provider\Role\ProviderInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Interfaces\StructureAwareInterface;

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
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        if (null === $this->roles) {
            $this->roles = array();

            // Chargement des rôles de base
            foreach( $this->config as $classname ){
                if (class_exists( $classname )){
                    $role = new $classname; /* @var $role \Zend\Permissions\Acl\Role\RoleInterface */
                    $this->roles[$role->getRoleId()] = $role;
                }else{
                    throw new \UnicaenApp\Exception\LogicException('La classe "'.$classname.'" déclarée dans la configuration du fournisseur de rôles n\'a pas été trouvée.');
                }
            }

            // chargement des rôles métiers
            $qb = $this->getEntityManager()->createQueryBuilder()
                ->from("Application\Entity\Db\Role", "r")
                ->select("r, tr, s")
                ->distinct()
                ->join("r.type", "tr")
                ->leftJoin("r.structure", "s");
            foreach ($qb->getQuery()->getResult() as $role) { /* @var $role \Application\Entity\Db\Role */
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
            }
        }
        return $this->roles;
    }
}
