<?php

namespace Application\Service;

use Application\Provider\Privilege\PrivilegeProviderInterface;
use \BjyAuthorize\Provider\Resource\ProviderInterface as ResourceProviderInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of Privilege
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Privilege extends AbstractEntityService implements PrivilegeProviderInterface, ResourceProviderInterface
{
    use \Application\Traits\SessionContainerTrait;


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

    public function getList(QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy($this->getAlias().'.ordre');
        return parent::getList($qb, $alias);
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
//        $session = $this->getSessionContainer();

//        if (! isset($session->privilegesRoles)){
            $privileges = $this->getList();
            /* @var $privileges \Application\Entity\Db\Privilege[] */

            $pr = [];
            foreach( $privileges as $privilege ){
                $roles = $privilege->getRoleCodes();
                if (! empty($roles)){
                    $pr[$privilege->getFullCode()] = $roles;
                }
            }return $pr;
//            $session->privilegesRoles = $pr;
//        }
//        return $session->privilegesRoles;
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