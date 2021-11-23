<?php

namespace Application\Service;

use Application\Entity\Db\AdresseNumeroCompl;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of AdresseNumeroComplService
 */
class AdresseNumeroComplService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return AdresseNumeroCompl::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'adrnc';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }
}