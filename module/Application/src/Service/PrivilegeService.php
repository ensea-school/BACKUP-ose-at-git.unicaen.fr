<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Provider\Resource\ProviderInterface;
use Intervenant\Entity\Db\Statut;
use Intervenant\Service\StatutServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Provider\Privilege\PrivilegeProviderInterface;

/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService implements PrivilegeProviderInterface, ProviderInterface
{
    use EntityManagerAwareTrait;
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;


    private array $privilegesCache       = [];

    private array $privilegesRolesConfig = [];



    /**
     * @param array $privilegesRolesConfig
     */
    public function __construct(array $privilegesRolesConfig)
    {
        $this->privilegesRolesConfig = $privilegesRolesConfig;
    }



    /**
     * Retourne un tableau à deux dimentions composé de chaînes de caractère UNIQUEMENT
     *
     * Format du tableau :
     * [
     *   'privilege_a' => ['role_1', ...],
     *   'privilege_b' => ['role_1', 'role_2', ...],
     * ]
     *
     * @return string[][]
     */
    public function getPrivilegesRoles()
    {
        if (empty($this->privilegesCache)) {
            $this->privilegesCache = $this->makePrivilegesRoles();
        }

        return $this->privilegesCache;
    }



    /**
     * @return array
     */
    public function getResources()
    {
        $resources  = [];
        $privileges = array_keys($this->getPrivilegesRoles());
        foreach ($privileges as $privilege) {
            $resources[] = Privileges::getResourceId($privilege);
        }

        return $resources;
    }



    public function makePrivilegesRoles()
    {
        $privilegesRoles = $this->privilegesRolesConfig;

        /* L'administrateur a tous les privilèges obligatoirement */
        $rc         = new \ReflectionClass(\Application\Provider\Privilege\Privileges::class);
        $privileges = array_values($rc->getConstants());
        foreach ($privileges as $privilege) {
            if (!isset($privilegesRoles[$privilege])) {
                $privilegesRoles[$privilege] = [];
            }
            $privilegesRoles[$privilege][] = Role::ADMINISTRATEUR;
        }

        $sql   = "
          SELECT
          cp.code || '-' || p.code privilege,
          r.code role
        FROM
          role_privilege rp
          JOIN privilege p ON p.id = rp.privilege_id
          JOIN categorie_privilege cp ON cp.id = p.categorie_id
          JOIN role r ON r.id = rp.role_id AND r.histo_destruction IS NULL
        ";
        $query = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while ($pr = $query->fetchAssociative()) {
            $privilege                     = $pr['PRIVILEGE'];
            $role                          = $pr['ROLE'];
            $privilegesRoles[$privilege][] = $role;
        }

        $statuts = $this->getServiceStatut()->getStatuts();
        foreach ($statuts as $statut) {
            $sp = $statut->getPrivileges();
            foreach ($sp as $privilege => $has) {
                if ($has) {
                    $privilegesRoles[$privilege][] = $statut->getRoleId();
                }
            }
        }

        return $privilegesRoles;
    }
}
