<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PermAffectAutreIntervMemeIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s permanent affecté dans une autre structure intervient dans ma structure";
    protected $pluralTitlePattern   = "%s permanents affectés dans une autre structure interviennent dans ma structure";
    
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
                ->join("s.volumeHoraire", "vh")
                ->join("vh.validation", "v"); // les volumes horaires doivent être validés.
        
        /**
         * Les permanents.
         */
        $qb->andWhere("ti.code = :type")->setParameter('type', \Application\Entity\Db\TypeIntervenant::CODE_PERMANENT);
        
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
        
        $qb->orderBy("int.nomUsuel, int.prenom");
         
        return $qb;
    }
}