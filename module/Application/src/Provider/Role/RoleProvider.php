<?php

namespace Application\Provider\Role;

use Application\Acl\Role;
use Application\Entity\Db\Affectation;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\PrivilegeServiceAwareTrait;
use BjyAuthorize\Provider\Role\ProviderInterface;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Permissions\Acl\Role\RoleInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la configuration du fournisseur
 *
 *
 */
class RoleProvider implements ProviderInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use StatutServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use ContextServiceAwareTrait;


    /**
     * @var array
     */
    protected $roles;

    /**
     * @var array
     */
    private $rolesPrivileges;



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



    public function clearRoles()
    {
        $this->roles = null;
    }



    protected function getRolesPrivileges()
    {
        if (!$this->rolesPrivileges) {
            $pr = $this->getServicePrivilege()->getPrivilegesRoles();
            foreach ($pr as $priv => $roles) {
                foreach ($roles as $role) {
                    if (!isset($this->rolesPrivileges[$role])) {
                        $this->rolesPrivileges[$role] = [];
                    }
                    $this->rolesPrivileges[$role][] = $priv;
                }
            }
        }

        return $this->rolesPrivileges;
    }



    protected function makeRoles()
    {
        $this->getServiceContext()->setInInit(true);
        $roles                  = [];
        $r                      = new Role();
        $roles[$r->getRoleId()] = $r;

        $intervenant = $this->getServiceContext()->getIntervenant();
        $utilisateur = $this->getServiceContext()->getUtilisateur();
        $structure   = $this->getServiceContext()->getStructure();

        // chargement des rôles métiers

        $query = $this->getEntityManager()->createQuery(
            'SELECT
            r, a, s, p
        FROM
            Application\Entity\Db\Role r
            JOIN r.perimetre p
            LEFT JOIN r.affectation a WITH a.histoDestruction IS NULL AND a.utilisateur = :utilisateur
            LEFT JOIN a.structure s
        WHERE
            r.histoDestruction IS NULL'
        )->setParameter('utilisateur', $utilisateur);
        $query->useResultCache(true);
        $query->setResultCacheId(str_replace('\\', '_', __CLASS__) . '_affectations');


        $result          = $query->getResult();
        $rolesPrivileges = $this->getRolesPrivileges();
        foreach ($result as $dbRole) {
            /* @var $dbRole \Application\Entity\Db\Role */
            $roleId = $dbRole->getRoleId();

            $role = new Role($roleId, 'user', $dbRole->getLibelle());
            if (isset($rolesPrivileges[$roleId])) {
                $role->initPrivileges($rolesPrivileges[$roleId]);
            }

            if ($dbRole->getPeutChangerStructure()) {
                $role->setPeutChangerStructure(true);
            }
            /* @var $role Role */
            $role->setDbRole($dbRole);
            $role->setPerimetre($dbRole->getPerimetre());

            // Si le rôle est de périmètre établissement, alors il se peut que l'on veuille zoomer sur une composante en particulier...
            if ($dbRole->getPerimetre()->isEtablissement() && $structure) {
                $role->setStructure($structure);
            }

            $roles[$roleId] = $role;

            $affectations = $dbRole->getAffectation();
            foreach ($affectations as $affectation) {
                /* @var $affectation Affectation */
                if ($affStructure = $affectation->getStructure()) {
                    $affRoleId = $roleId . '-' . $affStructure->getSourceCode();
                    if (!isset($roles[$affRoleId]) && $dbRole->estNonHistorise()) {
                        $affRoleLibelle = $dbRole->getLibelle() . ' (' . $affStructure->getLibelleCourt() . ')';
                        $affRole        = new \Application\Acl\Role($affRoleId, $roleId, $affRoleLibelle);
                        if (isset($rolesPrivileges[$roleId])) {
                            $affRole->initPrivileges($rolesPrivileges[$roleId]);
                        }
                        $affRole->setDbRole($dbRole);
                        $affRole->setPerimetre($dbRole->getPerimetre());
                        $affRole->setStructure($affStructure);
                        $roles[$affRoleId] = $affRole;
                    }
                }
            }
        }


        // Chargement des rôles par statut d'intervenant
        $statuts = $this->getServiceStatut()->getStatuts();
        foreach ($statuts as $statut) {
            $role = new Role($statut->getRoleId(), 'user', $statut->getTypeIntervenant()->getLibelle());

            if ($intervenant && $intervenant->getStatut() == $statut) {
                $role->setIntervenant($intervenant);
                if (isset($rolesPrivileges[$intervenant->getStatut()->getRoleId()])) {
                    $role->initPrivileges($rolesPrivileges[$intervenant->getStatut()->getRoleId()]);
                }
            }
            $roles[$statut->getRoleId()] = $role;
        }

        $this->getServiceContext()->setInInit(false);

        return $roles;
    }

}
