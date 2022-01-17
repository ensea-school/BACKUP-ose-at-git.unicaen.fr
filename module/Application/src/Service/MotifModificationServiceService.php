<?php

namespace Application\Service;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of MotifModificationServiceDu
 *
 */
class MotifModificationServiceService extends AbstractEntityService
{
    /**
     * retourne la classe des entit?s
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\MotifModificationServiceDu::class;
    }

    /**
     * Retourne l'alias d'entit? courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fr';
    }

    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery();
        return parent::getList($qb, $alias);
    }
}