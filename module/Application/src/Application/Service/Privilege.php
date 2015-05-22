<?php

namespace Application\Service;

use Application\Provider\Privilege\PrivilegeProviderInterface;
use \BjyAuthorize\Provider\Resource\ProviderInterface as ResourceProviderInterface;


/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Privilege extends AbstractEntityService implements PrivilegeProviderInterface, ResourceProviderInterface
{
    /**
     *
     * @var array
     */
    private $privilegesRoles;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Privilege';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'priv';
    }

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
        if (empty($this->privilegesRoles)){
            $privileges = $this->getList();
            /* @var $privileges \Application\Entity\Db\Privilege[] */

            $this->privilegesRoles = [];
            foreach( $privileges as $privilege ){
                $roles = $privilege->getRoleCodes();
                if (! empty($roles)){
                    $this->privilegesRoles[$privilege->getFullCode()] = $roles;
                }
            }
        }
        return $this->privilegesRoles;
    }

    public function getResources()
    {
        $resources = [];
        $privileges = array_keys( $this->getPrivilegesRoles() );
        foreach( $privileges as $privilege ){
            $resources[] = 'privilege/'.$privilege;
        }
        return $resources;
    }
}