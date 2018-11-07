<?php

namespace Application\Service;

use Application\Entity\Db\Pays;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of Pays
 */
class PaysService extends AbstractEntityService
{
    use ParametresServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return Pays::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'p';
    }



    /**
     * @return Pays
     */
    public function getFrance(): Pays
    {
        $franceId = $this->getServiceParametres()->get('pays_france');

        return $this->get($franceId);
    }



    /**
     * @param Pays $pays
     *
     * @return bool
     */
    public function isFrance(Pays $pays): bool
    {
        return $pays == $this->getFrance();
    }



    /**
     * Retourne la liste des pays, triés par libellé long.
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return PaysService[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");

        return parent::getList($qb, $alias);
    }
}