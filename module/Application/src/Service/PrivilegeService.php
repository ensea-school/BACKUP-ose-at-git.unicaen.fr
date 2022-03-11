<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Statut;
use UnicaenApp\Traits\SessionContainerTrait;

/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService extends \UnicaenAuth\Service\PrivilegeService
{
    use SessionContainerTrait;
    use ContextServiceAwareTrait;


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
        $annee = $this->getServiceContext()->getAnnee();

        $pk      = 'privileges' . $annee->getId();
        $session = $this->getSessionContainer();

        if (!isset($session->$pk)) {
            $session->$pk = $this->makePrivilegesRoles($annee);
        }

        return $session->$pk;
    }



    public function makePrivilegesRoles(Annee $annee)
    {
        $privilegesRoles = [];

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
            $privilege = $pr['PRIVILEGE'];
            $role      = $pr['ROLE'];
            if (!array_key_exists($privilege, $privilegesRoles)) {
                $privilegesRoles[$privilege] = [];
            }
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
                    if (!array_key_exists($privilege, $privilegesRoles)) {
                        $privilegesRoles[$privilege] = [];
                    }
                    $privilegesRoles[$privilege][] = $statut->getRoleId();
                }
            }
        }

        return $privilegesRoles;
    }
}
