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
        $r = new Role();                                        $roles[$r->getRoleId()] = $r;
        $r = new \Application\Acl\ComposanteRole();             $roles[$r->getRoleId()] = $r;
        $r = new \Application\Acl\DrhRole();                    $roles[$r->getRoleId()] = $r;
        $r = new \Application\Acl\EtablissementRole();          $roles[$r->getRoleId()] = $r;
        $r = new \Application\Acl\IntervenantRole();            $roles[$r->getRoleId()] = $r;
        $r = new \Application\Acl\IntervenantExterieurRole();   $roles[$r->getRoleId()] = $r;
        $r = new \Application\Acl\IntervenantPermanentRole();   $roles[$r->getRoleId()] = $r;
        
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
        $personnel = null;
        if ($utilisateur) $personnel = $utilisateur->getPersonnel();

        // chargement des rôles métiers
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from("Application\Entity\Db\Role", "r")
            ->select("r, a, s")
            ->distinct()
            ->leftJoin("r.affectation", "a", \Doctrine\ORM\Query\Expr\Join::WITH, '1=compriseEntre(a.histoCreation,a.histoDestruction) AND a.personnel = :personnel')
            ->leftJoin("a.structure", "s")
            ->andWhere('1=compriseEntre(r.histoCreation,r.histoDestruction)')
            ->setParameter(':personnel', $personnel);

        foreach ($qb->getQuery()->getResult() as $dbRole) { /* @var $dbRole \Application\Entity\Db\Role */
            $roleId = $dbRole->getCode();

            $roleClass = 'Application\Acl\Role';
            $parent = 'user';
            /** @deprecated */
            if ($roleId == 'gestionnaire-composante')   { $roleClass = 'Application\Acl\GestionnaireComposanteRole'; $parent='composante';}
            if ($roleId == 'directeur-composante')      { $roleClass = 'Application\Acl\DirecteurComposanteRole';    $parent='composante';}
            if ($roleId == 'administrateur')            { $roleClass = 'Application\Acl\AdministrateurRole';}
            if ($roleId == 'responsable-composante')    { $roleClass = 'Application\Acl\ResponsableComposanteRole';  $parent='composante';}
            if ($roleId == 'superviseur-etablissement') { $roleClass = 'Application\Acl\EtablissementRole'; }
            if ($roleId == 'gestionnaire-drh')          { $roleClass = 'Application\Acl\DrhRole'; }
            /* FIN de deprecated */

            $role = new $roleClass( $roleId, $parent, $dbRole->getLibelle() );
            $role->setDbRole($dbRole);
            $role->setPersonnel($personnel);

            $roles[$roleId] = $role;

            $affectations = $dbRole->getAffectation();
            foreach( $affectations as $affectation ){ /* @var $affectation Affectation */
                if ($structure = $affectation->getStructure()){
                    $affRoleId = $roleId.'-'.$structure->getSourceCode();
                    if (! isset($roles[$affRoleId])){
                        $affRoleLibelle = $dbRole->getLibelle().' ('.$structure->getLibelleCourt().')';
                        $affRole = new $roleClass( $affRoleId, $roleId, $affRoleLibelle );
                        $affRole->setDbRole( $dbRole );
                        $affRole->setPersonnel( $personnel );
                        $affRole->setStructure( $structure );
                        $roles[$affRoleId] = $affRole;
                    }
                }
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
