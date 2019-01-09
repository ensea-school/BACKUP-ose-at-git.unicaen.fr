<?php

namespace Application\Service;


/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService extends \UnicaenAuth\Service\PrivilegeService
{

    /**
     * Retourne un tableau à deux dimentions composé de chaînes de caractère UNIQUEMENT
     *
     * Format du tableau :
     * [
     *   'privilege_a' => ['role_1', ...],
     *   'privilege_b' => ['role_1', 'role_2', ...],
     * ]
     * @return string[][]
     */
    public function getPrivilegesRoles()
    {
        if (null === $this->privilegesRoles){
            $this->privilegesRoles = [];
            $sql = 'SELECT * FROM v_privileges_roles';
            $prl =  $this->getEntityManager()->getConnection()->query($sql)->fetchAll();
            foreach( $prl as $pr ){
                extract( array_change_key_case($pr,CASE_LOWER) );

                if (! array_key_exists($privilege,$this->privilegesRoles)){
                    $this->privilegesRoles[$privilege] = [];
                }
                if ($role){
                    $this->privilegesRoles[$privilege][] = $role;
                }
            }
        }
        return $this->privilegesRoles;
    }

}