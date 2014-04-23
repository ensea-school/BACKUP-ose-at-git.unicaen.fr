<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;


/**
 * Description of Periode
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Periode extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Periode';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'per';
    }

    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEnseignement( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.enseignement = 1");
        return $qb;
    }

    /**
     * Retourne la liste des périodes
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\Periode[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->orderBy("$alias.ordre");
        return parent::getList($qb, $alias);
    }
}