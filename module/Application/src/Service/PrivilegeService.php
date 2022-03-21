<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Role;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Statut;

/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService extends \UnicaenAuth\Service\PrivilegeService
{
    use ContextServiceAwareTrait;


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
            $annee                 = $this->getServiceContext()->getAnnee();
            $this->privilegesCache = $this->makePrivilegesRoles($annee);
        }

        return $this->privilegesCache;
    }



    public function makePrivilegesRoles(Annee $annee)
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

        /* L'administrateur a tous les privilèges obligatoirement */
        $rc         = new \ReflectionClass(\Application\Provider\Privilege\Privileges::class);
        $privileges = array_values($rc->getConstants());
        foreach ($privileges as $privilege) {
            $privilegesRoles[$privilege] = [Role::ADMINISTRATEUR];
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

        $dql   = "SELECT s FROM " . Statut::class . " s WHERE s.annee = :annee";
        $query = $this->getEntityManager()->createQuery($dql)->setParameter('annee', $annee);
        /** @var Statut[] $statuts */
        $statuts = $query->getResult();
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
