<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Traversable;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class SaisieServiceApresContratAvenantIndicateurImpl extends AbstractIndicateurImpl
{
    protected $titlePattern = "%s vacataires ont saisi des heures d'enseignement supplémentaires depuis l'édition de leur contrat ou avenant";
    
    /**
     * 
     * @return string
     */
    public function getTitle()
    {
        $title = sprintf($this->titlePattern, $this->getResultCount());
        
        if ($this->getStructure()) {
            $title .= " ({$this->getStructure()})";
        }
        
        return $title;
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
        return $this->getHelperUrl()->fromRoute('intervenant/validation-service', ['intervenant' => $result->getSourceCode()]);
    }
    
    /**
     * 
     * @return int
     */
    public function getResultCount()
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
     
        /**
         * NB: pas besoin de consulter la progression dans le workflow car si l'intervenant a déjà un contrat/avenant,
         * c'est qu'il a bien atteint l'étape "contrat".
         */
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("s.structureEns = :structure")
                    ->andWhere("c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
}