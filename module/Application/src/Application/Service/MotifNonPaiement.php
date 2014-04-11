<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\MotifNonPaiement as Entity;

/**
 * Description of MotifNonPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiement extends AbstractService
{

    /**
     * Repository
     *
     * @var Repository
     */
    protected $repo;

    /**
     * Liste des motifs de non paiement
     *
     * @var Entity[]
     */
    protected $motifsNonPaiement;




    /**
     * Retourne la liste des motifs de non paiement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByAll( QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('mnp');

        $qb->addOrderBy('mnp.libelleLong');

        return $qb;
    }

    /**
     *
     * @return EntityRepository
     */
    public function getRepo()
    {
        if( empty($this->repo) ){
            $this->getEntityManager()->getFilters()->enable("historique");
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\MotifNonPaiement');
        }
        return $this->repo;
    }

    /**
     * Liste des motifs de non paiement
     *
     * @return Entity[]
     */
    public function getMotifsNonPaiement()
    {
        if (! $this->motifsNonPaiement){
            $mnps = $this->finderByAll()->getQuery()->execute();

            $this->motifsNonPaiement = array();
            foreach( $mnps as $mnp ){
                $this->motifsNonPaiement[$mnp->getId()] = $mnp;
            }
        }
        return $this->motifsNonPaiement;
    }
}