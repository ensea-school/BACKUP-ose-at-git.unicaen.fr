<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PermAffectMemeIntervAutreIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s permanent affecté dans ma structure (%s) intervient dans une autre structure";
    protected $pluralTitlePattern   = "%s permanents affectés dans ma structure (%s) interviennent dans une autre structure";
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getStructure());
        $this->pluralTitlePattern = sprintf($this->pluralTitlePattern, '%s', $this->getStructure());
        
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
                ->join("vh.validation", "v"); // les volumes horaires doivent être validés.
        
        /**
         * Les permanents.
         */
        $qb->andWhere("ti.code = :type")->setParameter('type', \Application\Entity\Db\TypeIntervenant::CODE_PERMANENT);
        
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
}