<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;
use Application\Service\StatutIntervenant as StatutIntervenantService;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class IntervAffectMemeIntervAutreAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s intervenant '%s' affecté dans ma structure (%s) intervient dans une autre structure";
    protected $pluralTitlePattern   = "%s intervenants '%s' affectés dans ma structure (%s) interviennent dans une autre structure";
    protected $codeStatutIntervenant;
    protected $statutIntervenant;
    
    /**
     * @return StatutIntervenantEntity
     */
    protected function getStatutIntervenant()
    {
        if (null === $this->statutIntervenant) {
            $qb = $this->getServiceStatutIntervenant()->finderBySourceCode($this->codeStatutIntervenant);
            $this->statutIntervenant = $qb->getQuery()->getOneOrNullResult();
        }
        
        return $this->statutIntervenant;
    }
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getStatutIntervenant(), $this->getStructure());
        $this->pluralTitlePattern   = sprintf($this->pluralTitlePattern,   '%s', $this->getStatutIntervenant(), $this->getStructure());
        
        return parent::getTitle(false);
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
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
                ->join("int.service", "s")
                ->join("s.structureAff", "sa")
                ->join("s.structureEns", "se") // on ne s'intéresse pas aux enseignements fait dans un autre établissement
                ->join("s.volumeHoraire", "vh")
                ->join("vh.validation", "v") // les volumes horaires doivent être validés
                ->andWhere("si = :statut")->setParameter('statut', $this->getStatutIntervenant()); 
        
        /**
         * Intervenants affectés à la structure spécifiée.
         */
        $qb
                ->andWhere("s.structureAff = :structure")
                ->setParameter('structure', $this->getStructure());
        
        /**
         * Intervenant dans une autre structure que celle spécifiée.
         */
        $qb->andWhere("s.structureEns <> :structure");
        
        $qb->orderBy("int.nomUsuel, int.prenom");
         
        return $qb;
    }
    
    /**
     * @return StatutIntervenantService
     */
    protected function getServiceStatutIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationStatutIntervenant');
    }
}