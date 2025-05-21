<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Laminas\Session\Container;
use Application\Entity\Db\Role;

/**
 * Description of Role
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleService extends AbstractEntityService
{
    private static ?Container $session = null;



    /**
     * Retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return Role::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'r';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return $qb;
    }



    public static function getSession(): Container
    {
        if (null === self::$session) {
            self::$session = new Container('ROLE_SESSION_CONTAINER');
        }
        return self::$session;
    }



    public static function clearSession(): void
    {
        $session = self::getSession();
        $session->getManager()->getStorage()->clear();
    }
}