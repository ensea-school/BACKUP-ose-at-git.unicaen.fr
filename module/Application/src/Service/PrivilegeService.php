<?php

namespace Application\Service;

use Application\Entity\Db\Privilege;
use Application\Entity\Db\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Provider\Resource\ProviderInterface;
use Doctrine\ORM\EntityRepository;
use Intervenant\Service\StatutServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;
use UnicaenPrivilege\Provider\Privilege\PrivilegeProviderInterface;

/**
 * Description of PrivilegeService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService implements PrivilegeProviderInterface, ProviderInterface
{
    use EntityManagerAwareTrait;
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;


    private array $privilegesCache = [];

    private array $privilegesRolesConfig = [];

    private array $noAdminPrivileges = [
        Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION,
        Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION,
        Privileges::REFERENTIEL_PREVU_AUTOVALIDATION,
        Privileges::REFERENTIEL_REALISE_AUTOVALIDATION,
    ];



    /**
     * @param array $privilegesRolesConfig
     */
    public function __construct(array $privilegesRolesConfig)
    {
        $this->privilegesRolesConfig = $privilegesRolesConfig;
    }



    public function getRepo(): EntityRepository
    {
        return $this->getEntityManager()->getRepository(Privilege::class);
    }



    public function get($id): ?Privilege
    {
        return $this->getRepo()->find($id);
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
     * @return PrivilegeInterface[]
     */
    public function getList()
    {
        $qb = $this->getRepo()->createQueryBuilder('p')
                   ->addSelect('c')
                   ->join('p.categorie', 'c')
                   ->addOrderBy('c.libelle')
                   ->addOrderBy('p.ordre');

        return $qb->getQuery()->getResult();
    }



    public function makePrivilegesRoles(): array
    {
        $privilegesRoles = $this->privilegesRolesConfig;

        /* L'administrateur a tous les privilèges obligatoirement */
        $rc         = new \ReflectionClass(\Application\Provider\Privilege\Privileges::class);
        $privileges = array_values($rc->getConstants());
        foreach ($privileges as $privilege) {
            if (!in_array($privilege, $this->noAdminPrivileges)) {
                // On ne pet plus l'auto-validation à l'administrateur par défaut
                if (!isset($privilegesRoles[$privilege])) {
                    $privilegesRoles[$privilege] = [];
                }
                $privilegesRoles[$privilege][] = Role::ADMINISTRATEUR;
            }
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



    public function getResources(): array
    {
        $resources  = [];
        $privileges = array_keys($this->getPrivilegesRoles());
        foreach ($privileges as $privilege) {
            $resources[] = Privileges::getResourceId($privilege);
        }

        return $resources;
    }
}
