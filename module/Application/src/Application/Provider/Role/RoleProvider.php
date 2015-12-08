<?php

namespace Application\Provider\Role;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Service\Traits\PersonnelAwareTrait;
use BjyAuthorize\Provider\Role\ProviderInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Acl\Role;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Traits\SessionContainerTrait;
use Application\Service\Traits\IntervenantAwareTrait;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la configuration du fournisseur
 *
 *
 */
class RoleProvider implements ProviderInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use ServiceLocatorAwareTrait;
    use StatutIntervenantAwareTrait;
    use SessionContainerTrait;
    use IntervenantAwareTrait;
    use PersonnelAwareTrait;

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
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
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
        $roles                  = [];
        $r                      = new Role();
        $roles[$r->getRoleId()] = $r;
        $r                      = new \Application\Acl\ComposanteRole();
        $roles[$r->getRoleId()] = $r;
        $r                      = new \Application\Acl\EtablissementRole();
        $roles[$r->getRoleId()] = $r;
        $r                      = new \Application\Acl\IntervenantRole();
        $roles[$r->getRoleId()] = $r;
        $r                      = new \Application\Acl\IntervenantExterieurRole();
        $roles[$r->getRoleId()] = $r;
        $r                      = new \Application\Acl\IntervenantPermanentRole();
        $roles[$r->getRoleId()] = $r;

        $serviceAuthUserContext = $this->getServiceLocator()->get('AuthUserContext');
        /* @var $serviceAuthUserContext \UnicaenAuth\Service\UserContext */


        if ($ldapUser = $serviceAuthUserContext->getLdapUser()) {
            $numeroPersonnel = (integer)$ldapUser->getSupannEmpId();
            $intervenant     = $this->getServiceIntervenant()->getBySourceCode($numeroPersonnel);
            $personnel       = $this->getServicePersonnel()->getBySourceCode($numeroPersonnel);
        } else {
            $intervenant = null;
            $personnel = null;
        }

        /* Rôles du personnel */

        // chargement des rôles métiers
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->from("Application\Entity\Db\Role", "r")
            ->select("r, a, s, p")
            ->distinct()
            ->join("r.perimetre", "p")
            ->leftJoin("r.affectation", "a", \Doctrine\ORM\Query\Expr\Join::WITH, '1=compriseEntre(a.histoCreation,a.histoDestruction) AND a.personnel = :personnel')
            ->leftJoin("a.structure", "s")
            ->andWhere('1=compriseEntre(r.histoCreation,r.histoDestruction)')
            ->setParameter(':personnel', $personnel);

        foreach ($qb->getQuery()->getResult() as $dbRole) {
            /* @var $dbRole \Application\Entity\Db\Role */
            $roleId = $dbRole->getRoleId();

            $roleClass = \Application\Acl\Role::class;
            $parent    = 'user';
            /** @deprecated */
            if ($roleId == 'gestionnaire-composante') {
                $roleClass = \Application\Acl\GestionnaireComposanteRole::class;
                $parent    = 'composante';
            }
            if ($roleId == 'directeur-composante') {
                $roleClass = \Application\Acl\DirecteurComposanteRole::class;
                $parent    = 'composante';
            }
            if ($roleId == 'administrateur') {
                $roleClass = \Application\Acl\AdministrateurRole::class;
            }
            if ($roleId == 'responsable-composante') {
                $roleClass = \Application\Acl\ResponsableComposanteRole::class;
                $parent    = 'composante';
            }
            if ($roleId == 'superviseur-etablissement') {
                $roleClass = \Application\Acl\EtablissementRole::class;
                $parent    = 'etablissement';
            }
            /* FIN de deprecated */

            $role = new $roleClass($roleId, $parent, $dbRole->getLibelle());
            /* @var $role Role */
            $role->setPersonnel($personnel);
            $role->setPerimetre($dbRole->getPerimetre());

            // Si le rôle est de périmètre établissement, alors il se peut que l'on veuille zoomer sur une composante en particulier...
            if ($this->structureSelectionnee && $dbRole->getPerimetre()->isEtablissement()) {
                $role->setStructure($this->structureSelectionnee);
            }

            $roles[$roleId] = $role;

            $affectations = $dbRole->getAffectation();
            foreach ($affectations as $affectation) {
                /* @var $affectation Affectation */
                if ($structure = $affectation->getStructure()) {
                    $affRoleId = $roleId . '-' . $structure->getSourceCode();
                    if (!isset($roles[$affRoleId])) {
                        $affRoleLibelle = $dbRole->getLibelle() . ' (' . $structure->getLibelleCourt() . ')';
                        $affRole        = new $roleClass($affRoleId, $roleId, $affRoleLibelle);
                        $affRole->setPersonnel($personnel);
                        $affRole->setStructure($structure);
                        $roles[$affRoleId] = $affRole;
                    }
                }
            }
        }


        // Chargement des rôles par statut d'intervenant
        $si = $this->getStatutsInfo();
        foreach ($si as $statut) {
            $roleClass = $statut['role-class'];
            $role      = new $roleClass($statut['role-id'], $statut['parent'], $roles[$statut['parent']]->getRoleName());

            if ($intervenant) {
                if ($intervenant->getStatut()->getId() == $statut['statut-id']) {
                    $role->setIntervenant($intervenant);
                }
            }
            $roles[$statut['role-id']] = $role;
        }

        return $roles;
    }



    public function getStatutsInfo()
    {
        $session = $this->getSessionContainer();
        if (!isset($session->statutsInfo)) {
            $si      = [];
            $statuts = $this->getServiceStatutIntervenant()->getList();
            foreach ($statuts as $statut) {
                /** @deprecated */
                if ($statut->getTypeIntervenant()->getCode() === \Application\Entity\Db\TypeIntervenant::CODE_PERMANENT) {
                    $parent    = \Application\Acl\IntervenantPermanentRole::ROLE_ID;
                    $roleClass = \Application\Acl\IntervenantPermanentRole::class;
                } else {
                    $parent    = \Application\Acl\IntervenantExterieurRole::ROLE_ID;
                    $roleClass = \Application\Acl\IntervenantExterieurRole::class;
                }
                $si[] = [
                    'statut-id'  => $statut->getId(),
                    'role-id'    => $statut->getRoleId(),
                    'parent'     => $parent,
                    'role-class' => $roleClass,
                ];
            }
            $session->statutsInfo = $si;
        }

        return $session->statutsInfo;
    }



    public function setStructureSelectionnee(StructureEntity $structureSelectionnee = null)
    {
        $this->structureSelectionnee = $structureSelectionnee;

        return $this;
    }
}
