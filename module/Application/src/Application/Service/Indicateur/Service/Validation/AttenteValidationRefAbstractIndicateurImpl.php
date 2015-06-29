<?php

namespace Application\Service\Indicateur\Service\Validation;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteValidationRefAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s %s est  en attente de validation de son  référentiel <em>%s</em>";
    protected $pluralTitlePattern   = "%s %s sont en attente de validation de leur référentiel <em>%s</em>";
    
    /**
     * Témoin indiquant s'il faut que l'intervenant soit à l'étape concernée dans le WF pour être acceptable.
     * 
     * @var boolean
     */
    protected $findByWfEtapeCourante = true;
    
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
                ->join("int.serviceReferentiel", "s")
                ->join("s.fonction", "f")
                ->join("s.volumeHoraireReferentiel", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->setParameter('tvh', $this->getTypeVolumeHoraire());
        
        /**
         * L'intervenant doit être à l'étape concernée dans le WF.
         */
        if ($this->findByWfEtapeCourante) {
            $service = $this->getServiceLocator()->get('ApplicationIntervenant'); /* @var $service Intervenant */
            $service->finderByWfEtapeCourante($this->getWorkflowStepKey(), $qb);
        }
        
        /**
         * Filtrage par Type d'intervenant.
         */
        $qb
                ->andWhere("ti = :type")
                ->setParameter('type', $this->getTypeIntervenant());
        
        /**
         * Filtrage par structure d'intervention.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("f.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        /**
         * Les volumes horaires ne doivent pas être validés.
         */
        $qb
                ->leftJoin("vh.validation", "val")
                ->andWhere("val.id IS NULL");
        
        /**
         * Eviction des données historisées.
         */
        $qb
                ->andWhere("1 = pasHistorise(s)")
                ->andWhere("1 = pasHistorise(f)")
                ->andWhere("1 = pasHistorise(vh)")
                ->andWhere("1 = pasHistorise(val)");
        
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