<?php

namespace Application\Service;

use Application\Entity\Db\WfEtape as WfEtapeEntity;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of Service
 *
 * @author Bertrand
 */
class WfEtape extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return WfEtapeEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'e';
    }



    /**
     * Recherche une étapde par son code.
     *
     * @param string $code
     *
     * @return WfEtapeEntity
     */
    public function getByCode($code)
    {
        return $this->finderByCode($code)->getQuery()->getOneOrNullResult();
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->orderBy($alias . '.ordre');

        return $qb;
    }

}