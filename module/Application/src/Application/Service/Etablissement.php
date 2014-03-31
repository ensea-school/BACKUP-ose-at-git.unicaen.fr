<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Func;



/**
 * Description of Etablissement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Etablissement extends AbstractService
{

    /**
     * Repository
     *
     * @var Repository
     */
    protected $repo;




    /**
     * Recherche par libellé
     *
     * @param string $term
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function findByLibelle($term, QueryBuilder $qb=null)
    {
        $terms = explode( ' ', $term );

        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('e');

        $concatFields = array(
            'e.libelle',
            'e.departement',
            'e.localisation',
        );

        foreach ($concatFields as $field) {
            if (!isset($searchIn)) {
                $searchIn = $qb->expr()->concat($qb->expr()->literal(''), $field);
                continue;
            }

            $searchIn = $qb->expr()->concat(
                $searchIn,
                $qb->expr()->concat($qb->expr()->literal(' '), $field)
            );
        }

        $haystack = new Func( 'CONVERT', array( $searchIn, '?1' ) );
        $parameters = array(
            1 => 'US7ASCII'
        );

        $index = 2;
        foreach( $terms as $term ){
            $parameters[$index] = "%$term%";
            $qb->andWhere($qb->expr()->like($qb->expr()->upper($haystack), $qb->expr()->upper("CONVERT(?$index, ?1)")));
            $index++;
        }
        $qb->orderBy('e.libelle');
        $qb->setParameters( $parameters );

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
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\Etablissement');
        }
        return $this->repo;
    }
}