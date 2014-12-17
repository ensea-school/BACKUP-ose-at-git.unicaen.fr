<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class SaisieServiceApresContratAvenantIndicateurImpl extends AbstractIndicateurImpl
{
    const PATTERN_TITLE = "%s vacataires ont saisi des heures d'enseignement supplémentaires depuis l'édition de leur contrat ou avenant";
    
    /**
     * 
     */
    public function getTitle()
    {
        return sprintf(static::PATTERN_TITLE, $this->getResultCount());
    }
    
    /**
     * 
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
     * @return int
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
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("ie");
        $qb
                ->join("ie.contrat", "c")
                ->join("ie.service", "s")
                ->join("s.volumeHoraire", "vh", Join::WITH, "vh.contrat IS NULL");
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("s.structureEns = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
}