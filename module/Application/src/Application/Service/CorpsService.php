<?php

namespace Application\Service;

use Application\Entity\Db\Corps;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of Corps
 */
class CorpsService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return Corps::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'corps';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");

        return $qb;
    }
}