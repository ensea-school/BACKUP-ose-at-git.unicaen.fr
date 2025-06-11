<?php

namespace Agrement\Service;

use Agrement\Entity\Db\TblAgrement;
use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of TblAgrementService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TblAgrementService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TblAgrement::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tbla';
    }



    public function getAgrement($typeAgrement, $intervenant)
    {
        return [];
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.intervenant,$alias.structure");

        return $qb;
    }

}