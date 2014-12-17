<?php

namespace Application\Service\Indicateur;

use Traversable;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteContratIndicateurImpl extends AbstractIndicateurImpl
{
    const PATTERN_TITLE = "%s vacataires sont en attente de leur contrat";
    
    /**
     * 
     */
    public function getTitle()
    {
        return sprintf(static::PATTERN_TITLE, $this->getResultCount());
    }
    
    /**
     * 
     * @return Traversable
     */
    public function getResult()
    {
        if (null === $this->result) {
            $qb = $this->getQueryBuilder();
//            print_r($qb->getQuery()->getSQL());

            $this->result = $qb->getQuery()->getResult();
        }
            
        return $this->result;
    }
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute('intervenant/contrat', ['intervenant' => $result->getSourceCode()]);
    }
    
    /**
     * 
     * @return integer
     */
    protected function getResultCount()
    {
        if (null !== $this->result) {
            return count($this->result);
        }
        
        $qb = $this->getQueryBuilder()->select("COUNT(DISTINCT ie)");
//        print_r($qb->getQuery()->getSQL());
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("ie");
        $qb
                ->join("ie.contrat", "c")
                ->join("ie.service", "s")
                ->join("s.volumeHoraire", "vh", \Doctrine\ORM\Query\Expr\Join::WITH, "vh.contrat IS NULL");
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("s.structureEns = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
}