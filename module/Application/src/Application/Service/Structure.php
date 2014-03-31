<?php

namespace Application\Service;

use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Func;
use Application\Entity\Db\Structure as EntityStructure;


/**
 * Description of Structure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Structure extends AbstractService
{

    /**
     * Repository
     *
     * @var Repository
     */
    protected $repo;





    /**
     * Retourne le contexte global des services
     *
     * @todo implémenter la notion de structure courante
     */
    public function getGlobalContext()
    {
//        $currentUser = $this->getServiceLocator()->get('authUserContext')->getDbUser();
        return array(
//            'structure'     => null,
        );
    }

    /**
     * Retourne la liste des structures selon le contexte donné
     *
     * @param array $context
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext( array $context, QueryBuilder $qb=null )
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('s');

        if (! empty($context['structure']) && $context['structure'] instanceof EntityStructure){
            $qb->andWhere('s.parente = :structure')->setParameter('structure', $context['structure']);
        }
        return $qb;
    }

    /**
     * Recherche par nom
     *
     * @param string $term
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByNom($term, QueryBuilder $qb=null)
    {
        $term = str_replace(' ', '', $term);

        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('s');

        $libelleLong = new Func('CONVERT', array('s.libelleLong', '?3') );
        $libelleCourt = new Func('CONVERT', array('s.libelleCourt', '?3') );

        $qb
                ->where('s.sourceCode = ?1')
                ->orWhere($qb->expr()->like($qb->expr()->upper($libelleLong), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($libelleCourt), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orderBy('s.libelleCourt');

        $qb->setParameters(array(1 => $term, 2 => "%$term%", 3 => 'US7ASCII'));

        //print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;

        return $qb;
    }

    /**
     * Retourne le chercheur des structures distinctes.
     *
     * @param int $niveau
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderDistinctStructures($niveau = null, QueryBuilder $qb=null)
    {
        if (empty($qb)) $qb = $this->getRepo()->createQueryBuilder('s');

                $qb->select('partial s.{id, libelleCourt}')
                ->distinct()
                ->from('Application\Entity\Db\Structure', 's')
//                ->innerJoin('s.elementPedagogique', 'ep')
                ->orderBy('s.libelleCourt');

        if (null !== $niveau) {
            $qb->where('s.niveau = ?', $niveau);
        }

        // provisoire
        $qb->where('s.parente = :ucbn')->setParameter('ucbn', $this->getEntityManager()->find('Application\Entity\Db\Structure', 8464));

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
            $this->repo = $this->getEntityManager()->getRepository('Application\Entity\Db\Structure');
        }
        return $this->repo;
    }
}