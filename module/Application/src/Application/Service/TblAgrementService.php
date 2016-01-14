<?php

namespace Application\Service;

use Application\Entity\Db\TblAgrement;
use Application\Entity\Db\TypeAgrement as TypeAgrementEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of TblAgrementService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TblAgrementService extends AbstractEntityService
{

    /**
     * @var array
     */
    private $needIntervenantAgrementCache = [];

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



    /**
     * Détermine si le type d'agrément concerne l'intervenant ou non
     *
     * @param TypeAgrementEntity $typeAgrement
     * @param IntervenantEntity  $intervenant
     * @param boolean            $useCache
     *
     * @return bool
     */
    public function needIntervenantAgrement(TypeAgrementEntity $typeAgrement, IntervenantEntity $intervenant, $useCache = true)
    {
        $taid = $typeAgrement->getId();
        $iid = $intervenant->getId();

        if ($useCache && isset($this->needIntervenantAgrementCache[$taid][$iid])){
            return $this->needIntervenantAgrementCache[$taid][$iid];
        }

        $qb = null;
        $qb = $this->finderByIntervenant($intervenant, $qb);
        $qb = $this->finderByTypeAgrement($typeAgrement, $qb);

        $result = $this->count($qb) > 0;

        if ($useCache) {
            $this->needIntervenantAgrementCache[$taid][$iid] = $result;
        }

        return $result;
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.intervenant,$alias.structure");

        return $qb;
    }

}