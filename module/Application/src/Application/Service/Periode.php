<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeIntervenant as EntityTypeIntervenant;


/**
 * Description of Periode
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Periode extends AbstractService
{

    /**
     * Repository
     *
     * @var Repository
     */
    protected $repo;

    /**
     * Périodes par types d'intervenants
     *
     * @var array[]
     */
    protected $periodesByTypeIntervenant;





    /**
     * Retourne la liste des périodes pour un type d'intervenant donné
     *
     * @param EntityTypeIntervenant $typeIntervenant
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByTypeIntervenant( EntityTypeIntervenant $typeIntervenant, QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('p');

        $qb->andWhere('p.typeIntervenant = :type')->setParameter('type', $typeIntervenant);
        $qb->orderBy('p.ordre');
        return $qb;
    }


    /**
     *
     * @param EntityTypeIntervenant $typeIntervenant
     * @return type
     */
    public function getByTypeIntervenant( EntityTypeIntervenant $typeIntervenant )
    {
        if (! isset($this->periodesByTypeIntervenant[$typeIntervenant->getId()])){
            $periodes = $this->finderByTypeIntervenant( $typeIntervenant )->getQuery()->execute();
            $this->periodesByTypeIntervenant[$typeIntervenant->getId()] = array();
            foreach( $periodes as $periode ){
                $this->periodesByTypeIntervenant[$typeIntervenant->getId()][$periode->getId()] = $periode;
            }
        }
        return $this->periodesByTypeIntervenant[$typeIntervenant->getId()];
    }


    /**
     *
     * @return EntityRepository
     */
    public function getRepo()
    {
        if( empty($this->repo) ){
            $this->getEntityManager()->getFilters()->enable("historique");
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\Periode');
        }
        return $this->repo;
    }
}