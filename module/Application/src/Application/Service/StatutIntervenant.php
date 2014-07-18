<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;

/**
 * Description of StatutIntervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutIntervenant extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\StatutIntervenant';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'si';
    }

    /**
     * Retourne la liste des statuts correspondant aux vacataires autres que BIATSS.
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderVacatairesNonBiatss(QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
    $qb->andWhere($qb->expr()->in('si.sourceCode', (new StatutIntervenantEntity)->vacatairesNonBiatss));
        return $qb;
    }

    /**
     * Retourne la liste des statuts correspondant aux vacataires autres que les chargés d'enseigenement pour 1 an.
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderVacatairesNonChargeEns1An(QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->andWhere('si.sourceCode <> :code')->setParameter('code', StatutIntervenantEntity::CHARG_ENS_1AN);
        return $qb;
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\StatutIntervenant[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->orderBy("$alias.id");
        return parent::getList($qb, $alias);
    }
}