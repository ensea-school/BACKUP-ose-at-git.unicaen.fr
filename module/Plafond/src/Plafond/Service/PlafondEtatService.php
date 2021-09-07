<?php

namespace Plafond\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Plafond\Entity\Db\PlafondEtat;

/**
 * Description of PlafondEtatService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method PlafondEtat get($id)
 * @method PlafondEtat[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method PlafondEtat newEntity()
 *
 */
class PlafondEtatService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PlafondEtat::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'plaetat';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }

}