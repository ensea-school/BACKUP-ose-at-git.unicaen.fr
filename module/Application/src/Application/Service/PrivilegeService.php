<?php

namespace Application\Service;

use Application\Cache\Traits\CacheContainerTrait;

/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService extends \UnicaenAuth\Service\PrivilegeService
{
    use CacheContainerTrait;



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
        if (null === $this->privilegesRoles) {
            $cc = $this->getCacheContainer();
            if (!isset($cc->privilegesRoles)) {
                $cc->privilegesRoles = $this->makePrivilegesRoles();
            }
            $this->privilegesRoles = $cc->privilegesRoles;
        }

        return $this->privilegesRoles;
    }



    private function makePrivilegesRoles()
    {
        $privilegesRoles = [];
        $sql                   = 'SELECT * FROM v_privileges_roles';
        $prl                   = $this->getEntityManager()->getConnection()->query($sql)->fetchAll();
        foreach ($prl as $pr) {
            extract(array_change_key_case($pr, CASE_LOWER));

            if (!array_key_exists($privilege, $privilegesRoles)) {
                $privilegesRoles[$privilege] = [];
            }
            if ($role) {
                $privilegesRoles[$privilege][] = $role;
            }
        }

        return $privilegesRoles;
    }
}
