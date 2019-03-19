<?php

namespace Application\Provider\Role;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Structure;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Provider\Role\ProviderInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Privilege\PrivilegeProviderAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Acl\Role;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;

/**
 * Fournisseur des rôles utilisateurs de l'application :
 * - ceux définis dans la configuration du fournisseur
 *
 *
 */
class RoleProvider implements ProviderInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use SessionContainerTrait;
    use IntervenantServiceAwareTrait;
    use PrivilegeProviderAwareTrait;
    use ContextServiceAwareTrait;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var Structure
     */
    protected $structureSelectionnee;

    /**
     * @var array
     */
    private $rolesPrivileges;



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



    protected function getRolesPrivileges()
    {
        if (!$this->rolesPrivileges) {
            $pr = $this->getPrivilegeProvider()->getPrivilegesRoles();
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
        $roles                  = [];
        $r                      = new Role();
        $roles[$r->getRoleId()] = $r;

        $intervenant = $this->getServiceContext()->getIntervenant();
        $utilisateur = $this->getServiceContext()->getUtilisateur();

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
        $query->setResultCacheId(__CLASS__.'/affectations');


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
                        $affRole        = new \Application\Acl\Role($affRoleId, $roleId, $affRoleLibelle);
                        if (isset($rolesPrivileges[$roleId])) {
                            $affRole->initPrivileges($rolesPrivileges[$roleId]);
                        }
                        $affRole->setDbRole($dbRole);
                        $affRole->setStructure($structure);
                        $roles[$affRoleId] = $affRole;
                    }
                }
            }
        }


        // Chargement des rôles par statut d'intervenant
        $si = $this->getStatutsInfo();
        foreach ($si as $statut) {
            $role = new Role($statut['role-id'], 'user', $statut['role-name']);

            if ($intervenant) {
                if ($intervenant->getStatut()->getId() == $statut['statut-id']) {
                    $role->setIntervenant($intervenant);
                    if (isset($rolesPrivileges[$intervenant->getStatut()->getRoleId()])) {
                        $role->initPrivileges($rolesPrivileges[$intervenant->getStatut()->getRoleId()]);
                    }
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
                $si[] = [
                    'statut-id' => $statut->getId(),
                    'role-id'   => $statut->getRoleId(),
                    'role-name' => $statut->getTypeIntervenant()->getLibelle(),
                ];
            }
            $session->statutsInfo = $si;
        }

        return $session->statutsInfo;
    }



    public function setStructureSelectionnee(Structure $structureSelectionnee = null)
    {
        $this->structureSelectionnee = $structureSelectionnee;

        return $this;
    }
}
