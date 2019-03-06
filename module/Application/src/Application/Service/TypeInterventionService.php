<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeInterventionStatut;

/**
 * Description of TypeIntervention
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeIntervention::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ti';
    }



    /**
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $annee = $this->getServiceContext()->getAnnee();

        $qb->andWhere( ''.$alias.'.anneeDebut IS NULL OR '.$alias.'.anneeDebut <= '.$annee->getId());
        $qb->andWhere( ''.$alias.'.anneeFin IS NULL OR '.$alias.'.anneeFin >= '.$annee->getId());

        //$this->finderByVisible(true, $qb);

        return $qb;
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
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return $qb;
    }


    /**
     * Retourne une entité à partir de son code
     * Retourne null si le code est null
     *
     * @param string|string[] $code
     *
     * @return TypeIntervention|TypeIntervention[]|null
     */
    public function getByCode($code)
    {
        if (is_array($code)) {
            list($qb, $alias) = $this->initQuery();
            $qb->andWhere($alias . '.code IN (:' . $alias . '_code)')->setParameter($alias . '_code', $code);

            return $this->getList($qb);
        } elseif ($code) {
            return $this->getRepo()->findOneBy(['code' => $code]);
        } else {
            return null;
        }
    }
}