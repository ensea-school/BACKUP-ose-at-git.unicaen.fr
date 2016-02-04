<?php

namespace Application\Service;

use Application\Entity\Db\TblAgrement;
use Application\Entity\Db\TypeAgrement as TypeAgrementEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
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
     * @param StructureEntity    $structure
     * @param boolean            $useCache
     *
     * @return bool
     */
    public function needIntervenantAgrement(TypeAgrementEntity $typeAgrement, IntervenantEntity $intervenant, StructureEntity $structure=null, $useCache = true)
    {

        $taid = $typeAgrement->getId();
        $iid = $intervenant->getId();
        if ($structure){
            $sid = $structure->getId();
        }else{
            $sid = 0;
        }

        if ($useCache && isset($this->needIntervenantAgrementCache[$taid][$iid][$sid])){
            return $this->needIntervenantAgrementCache[$taid][$iid][$sid];
        }

        $qb = $this->finderByIntervenant($intervenant);
        $this->finderByTypeAgrement($typeAgrement, $qb);
        $this->finderByAtteignable(true, $qb); // l'étape doit être atteignable, en attendant mieux...
        if ($structure){
            $qb->andWhere('('.$this->getAlias().'.structure = :structure OR '.$this->getAlias().'.structure IS NULL)');
            $qb->setParameter('structure', $structure);
        }

        $result = $this->count($qb) > 0;

        if ($useCache) {
            $this->needIntervenantAgrementCache[$taid][$iid][$sid] = $result;
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