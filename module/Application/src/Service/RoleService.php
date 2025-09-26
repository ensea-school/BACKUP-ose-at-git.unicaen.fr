<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of Role
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\Role::class;
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
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb,
         $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return $qb;
    }

}