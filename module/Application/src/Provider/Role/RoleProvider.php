<?php

namespace Application\Provider\Role;

use Application\Acl\Role;
use Application\Entity\Db\Affectation;
use Application\Service\RoleService;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Provider\Role\ProviderInterface;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Permissions\Acl\Role\RoleInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Provider\Privilege\PrivilegeProviderAwareTrait;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la configuration du fournisseur
 *
 *
 */
class RoleProvider implements ProviderInterface, EntityManagerAwareInterface
{
    const AFFECTATIONS_CACHE_ID = 'Application_Provider_RoleRoleProvider_affectations';

    use EntityManagerAwareTrait;
    use StatutServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use PrivilegeProviderAwareTrait;
    use ContextServiceAwareTrait;


    /**
     * @return RoleInterface[]
     */
    public function getRoles(): array
    {
        $session = RoleService::getSession();
        if (!$session->offsetExists('roles') || empty($session->roles)) {
            $session->roles = $this->makeRoles();
        }
        return $session->roles;
    }



    public function clearRoles(): void
    {
        $session = RoleService::getSession();
        if ($session->offsetExists('roles')){
            $session->offsetUnset('roles');
        }
    }



    protected function getRolesPrivileges()
    {
        $session = RoleService::getSession();
        if (!$session->offsetExists('rolesPrivileges') || empty($session->rolesPrivileges)) {
            $rolesPrivileges = [];
            $pr              = $this->getPrivilegeProvider()->getPrivilegesRoles();
            foreach ($pr as $priv => $roles) {
                foreach ($roles as $role) {
                    if (!isset($this->rolesPrivileges[$role])) {
                        $rolesPrivileges[$role] = [];
                    }
                    $rolesPrivileges[$role][] = $priv;
                }
            }
            $session->rolesPrivileges = $rolesPrivileges;
        }

        return $session->rolesPrivileges;
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
        $query->enableResultCache(true);
        $query->setResultCacheId(self::AFFECTATIONS_CACHE_ID);


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
