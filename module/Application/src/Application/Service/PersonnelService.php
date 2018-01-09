<?php

namespace Application\Service;

use Application\Entity\Db\Personnel;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of Personnel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PersonnelService extends AbstractEntityService
{
    use ParametresServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Personnel::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'personnel';
    }



    /**
     *
     * @param string $sourceCode
     *
     * @return Personnel
     */
    public function getBySourceCode($sourceCode)
    {
        if (null == $sourceCode) return null;

        return $this->getRepo()->findOneBy(['sourceCode' => $sourceCode]);
    }



    /**
     * Retourne le directeur des ressources humaines, s'il est défini.
     *
     * @return Personnel
     */
    public function getDrh()
    {
        $drhId = $this->getServiceParametres()->get('directeur_ressources_humaines_id');

        return $this->get($drhId);
    }



    /**
     * Recherche par :
     * - id source exact (numéro Harpege ou autre),
     * - ou nom usuel (et prénom),
     * - ou nom patronymique (et prénom).
     *
     * @param string $term
     *
     * @return QueryBuilder
     */
    public function finderByTerm($term, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $term = str_replace(' ', '', $term);

        $concatNomUsuelPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.nomUsuel', $alias . '.prenom'),
             '?3']);
        $concatNomPatroPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.nomPatronymique', $alias . '.prenom'),
             '?3']);
        $concatPrenomNomUsuel = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.prenom', $alias . '.nomUsuel'),
             '?3']);
        $concatPrenomNomPatro = new \Doctrine\ORM\Query\Expr\Func('CONVERT',
            [$qb->expr()->concat($alias . '.prenom', $alias . '.nomPatronymique'),
             '?3']);

        $qb
//                ->select('i.')
            ->where($alias . '.sourceCode = ?1')
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomUsuelPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomPatroPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomUsuel), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomPatro), $qb->expr()->upper('CONVERT(?2, ?3)')))
            ->orderBy($alias . '.nomUsuel, ' . $alias . '.prenom');

        $qb->setParameters([1 => $term, 2 => "%$term%", 3 => 'US7ASCII']);

//        print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;

        return $qb;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");

        return $qb;
    }
}