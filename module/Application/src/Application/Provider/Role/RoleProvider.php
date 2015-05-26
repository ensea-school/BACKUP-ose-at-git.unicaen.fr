<?php

namespace Application\Provider\Role;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Structure as StructureEntity;
use BjyAuthorize\Provider\Role\ProviderInterface;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Acl\Role;
use Application\Acl\IntervenantRole;

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
     * @var StructureEntity
     */
    protected $structureSelectionnee;



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
$roles['test'] = new Role( 'test', 'user', 'Rôle de test');
        /* deprecated */
        foreach( $this->config as $classname ){
            if (class_exists( $classname )){
                $role = new $classname; /* @var $role RoleInterface */
                $roles[$role->getRoleId()] = $role;
            }else{
                throw new LogicException('La classe "'.$classname.'" déclarée dans la configuration du fournisseur de rôles n\'a pas été trouvée.');
            }
        }
        /* fin de deprecated */
        
        $serviceAuthUserContext = $this->getServiceLocator()->get('AuthUserContext');
        /* @var $serviceAuthUserContext \UnicaenAuth\Service\UserContext */
        $utilisateur = $serviceAuthUserContext->getDbUser();


        /* Cas spécifique du rôle intervenant */
        if ($utilisateur && $utilisateur->getIntervenant()){
            $role = new IntervenantRole;
            $role->setIntervenant( $utilisateur->getIntervenant() );
            $roles[$role->getRoleId()] = $role;
        }

        /* Rôles du personnel */
        if ($utilisateur && ($personnel = $utilisateur->getPersonnel())){
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
                 $dbRole = $affectation->getRole();

                $roleId = $dbRole->getCode();
                $roleLibelle = $dbRole->getLibelle();
                if ($structure = $affectation->getStructure()){
                    $roleId .= '-'.$structure->getSourceCode();
                    $roleLibelle .= ' ('.$structure->getLibelleCourt().')';
                }

                /** @deprecated */
                $parents = [
                    'gestionnaire-composante',
                    'responsable-recherche-labo',
                    'directeur-composante',
                    'administrateur',
                    'responsable-composante',
                    'superviseur-etablissement',
                ];
                if (in_array($dbRole->getCode(), $parents)){
                    $parent = $dbRole->getCode();
                }else{
                    $parent = 'user';
                }

                if (isset($roles[$roleId])){
                    $role = $roles[$roleId];
                }else{
                    $role = new Role( $roleId, $parent, $roleLibelle);
                }

                /* fin de deprecated */

                //$role = new Role( $roleId, 'user', $roleLibelle);
                $role->setDbRole( $dbRole );
                $role->setPersonnel( $personnel );

                if ($this->structureSelectionnee){
                    $role->setStructure( $this->structureSelectionnee );
                }else{
                    $role->setStructure( $affectation->getStructure() );
                }

                $roles[$roleId] = $role;
            }
        }
        return $roles;
    }
    
    public function setStructureSelectionnee(StructureEntity $structureSelectionnee = null)
    {
        $this->structureSelectionnee = $structureSelectionnee;
        
        return $this;
    }
}
