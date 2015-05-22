<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteValidationEnsAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s %s est en attente de validation de ses enseignements <em>%s</em>";
    protected $pluralTitlePattern   = "%s %s sont en attente de validation de leurs enseignements <em>%s</em>";
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf(
                $this->singularTitlePattern, 
                '%s', 
                TypeIntervenantEntity::CODE_EXTERIEUR === $this->getTypeIntervenant()->getCode() ? "vacataire" : "permanent", 
                $this->getTypeVolumeHoraire());
        $this->pluralTitlePattern   = sprintf(
                $this->pluralTitlePattern,   
                '%s', 
                TypeIntervenantEntity::CODE_EXTERIEUR === $this->getTypeIntervenant()->getCode() ? "vacataires" : "permanents", 
                $this->getTypeVolumeHoraire());
        
        return parent::getTitle($appendStructure);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
                ->join("int.service", "s")
                ->join("s.elementPedagogique", "ep")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->setParameter('tvh', $this->getTypeVolumeHoraire());
        
        /**
         * L'intervenant doit être à l'étape concernée dans le WF.
         */
        $service = $this->getServiceLocator()->get('ApplicationIntervenant'); /* @var $service Intervenant */
        $service->finderByWfEtapeCourante($this->getWorkflowStepKey(), $qb);
        
        /**
         * Type d'intervenant.
         */
//        $qb->andWhere("ti.code = :type")->setParameter('type', TypeIntervenantEntity::CODE_EXTERIEUR);
        $qb->andWhere("ti = :type")->setParameter('type', $this->getTypeIntervenant());
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("ep.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        /**
         * Les volumes horaires ne doivent pas être validés.
         */
        $qb->andWhere("vh.validation IS EMPTY");
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    abstract protected function getTypeIntervenant();
    
    /**
     * Retourne le type de volume horaire utile à cet indicateur.
     * 
     * @return TypeVolumeHoraireEntity
     */
    abstract protected function getTypeVolumeHoraire();
    
    /**
     * Retourne la clé de l'étape utile à cet indicateur.
     * 
     * @return string
     */
    abstract protected function getWorkflowStepKey();
}