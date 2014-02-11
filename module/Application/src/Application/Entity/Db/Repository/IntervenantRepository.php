<?php

namespace Application\Entity\Db\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * IntervenantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IntervenantRepository extends EntityRepository
{
    /**
     * Recherche par :
     * - id source exact (numéro Harpege ou autre), 
     * - ou nom usuel (et prénom), 
     * - ou nom patronymique (et prénom).
     * 
     * @param string $term
     * @return \Application\Entity\Db\Intervenant[]
     */
    public function findByNomPrenomIdQueryBuilder($term)
    {
        
    }
    
    /**
     * Recherche par :
     * - id source exact (numéro Harpege ou autre), 
     * - ou nom usuel (et prénom), 
     * - ou nom patronymique (et prénom).
     * 
     * @param string $term
     * @return \Application\Entity\Db\Intervenant[]
     */
    public function findByNomPrenomId($term)
    {
        $term = str_replace(' ', '', $term);
        
        $qb = $this->createQueryBuilder('i');
        
        $concatNomUsuelPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat('i.nomUsuel', 'i.prenom'), 
                '?3'));
        $concatNomPatroPrenom = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat('i.nomPatronymique', 'i.prenom'), 
                '?3'));
        $concatPrenomNomUsuel = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat('i.prenom', 'i.nomUsuel'), 
                '?3'));
        $concatPrenomNomPatro = new \Doctrine\ORM\Query\Expr\Func('CONVERT', 
                array($qb->expr()->concat('i.prenom', 'i.nomPatronymique'), 
                '?3'));
        
        $qb
//                ->select('i.')
                ->where('i.sourceCode = ?1')
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomUsuelPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatNomPatroPrenom), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomUsuel), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orWhere($qb->expr()->like($qb->expr()->upper($concatPrenomNomPatro), $qb->expr()->upper('CONVERT(?2, ?3)')))
                ->orderBy('i.nomUsuel, i.prenom');
        
        $qb->setParameters(array(1 => $term, 2 => "%$term%", 3 => 'US7ASCII'));
        
//        print_r($qb->getQuery()->getSQL()); var_dump($qb->getQuery()->getParameters());die;
        
        return $qb->getQuery()->execute();
    }
}