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
class Structure extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Structure';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'str';
    }

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
    public function finderByContext( array $context, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        if (! empty($context['structure']) && $context['structure'] instanceof EntityStructure){
            $qb->andWhere("$alias.parente = :structure")->setParameter('structure', $context['structure']);
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
    public function finderByNom($term, QueryBuilder $qb=null, $alias=null)
    {
        $term = str_replace(' ', '', $term);

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $libelleLong = new Func('CONVERT', array("$alias.libelleLong", '?3') );
        $libelleCourt = new Func('CONVERT', array("$alias.libelleCourt", '?3') );

        $qb
                ->where("$alias.sourceCode = ?1")
                ->orWhere($qb->expr()->like($qb->expr()->upper($libelleLong), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($libelleCourt), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orderBy("$alias.libelleCourt");

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
    public function finderDistinctStructures($niveau = null, QueryBuilder $qb=null, $alias=null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

                $qb->select("partial $alias.{id, libelleCourt}")
                ->distinct()
                ->from($this->getEntityClass(), $alias)
//                ->innerJoin("$alias.elementPedagogique", 'ep')
                ->orderBy("$alias.libelleCourt");

        if (null !== $niveau) {
            $qb->where("$alias.niveau = ?", $niveau);
        }

        // provisoire
        $qb->where("$alias.parente = :ucbn")->setParameter('ucbn', $this->getEntityManager()->find('Application\Entity\Db\Structure', 8464));

        return $qb;
    }
}