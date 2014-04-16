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
        return $qb;
    }

    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEnseignement( QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('p');

        $qb->andWhere('p.enseignement = 1');
        return $qb;
    }

    /**
     * Retourne la liste des périodes
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\Periode[]
     */
    public function getList( QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('p');

        $qb->orderBy('p.ordre');
        $periodes = $qb->getQuery()->execute();
        $result = array();
        foreach( $periodes as $periode ){
            $result[$periode->getId()] = $periode;
        }
        return $result;
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