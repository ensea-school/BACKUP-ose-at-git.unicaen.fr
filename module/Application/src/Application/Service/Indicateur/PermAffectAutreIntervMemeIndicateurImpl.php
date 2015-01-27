<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;
use Traversable;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PermAffectAutreIntervMemeIndicateurImpl extends AbstractIndicateurImpl
{
    protected $singularTitlePattern = "%s permanent affecté dans une autre structure intervient dans ma structure";
    protected $pluralTitlePattern   = "%s permanents affectés dans une autre structure interviennent dans ma structure";
    
    /**
     * 
     * @return Traversable
     */
    public function getResult()
    {
        if (null === $this->result) {
            $qb = $this->getQueryBuilder();
            $qb->addOrderBy("i.nomUsuel, i.prenom");
            
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
        return $this->getHelperUrl()->fromRoute(
                'intervenant/services', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * 
     * @return integer
     */
    public function getResultCount()
    {
        if (null !== $this->result) {
            return count($this->result);
        }
        
        $qb = $this->getQueryBuilder()->select("COUNT(DISTINCT i)");
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantPermanent')->createQueryBuilder("i");
        $qb
                ->join("i.service", "s")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.validation", "v"); // les volumes horaires doivent être validés.
        
        /**
         * Intervenants affectés à une autre structure que celle spécifiée.
         */
        $qb->andWhere("s.structureAff <> :structure");
        
        /**
         * Intervenant dans la structure spécifiée.
         */
        $qb
                ->andWhere("s.structureEns = :structure")
                ->setParameter('structure', $this->getStructure());
        
        $qb->orderBy("i.nomUsuel, i.prenom");
         
        return $qb;
    }
}