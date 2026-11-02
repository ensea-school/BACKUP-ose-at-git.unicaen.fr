<?php

namespace Service\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Service\Entity\Db\MotifModificationServiceDu;

class MotifModificationServiceDuService extends AbstractEntityService
{
    /**
     * retourne la classe des entit?s
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return MotifModificationServiceDu::class;
    }



    /**
     * Retourne l'alias d'entit? courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'mmsd';
    }



    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }



    public function getList(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery();

        return parent::getList($qb, $alias);
    }
}
