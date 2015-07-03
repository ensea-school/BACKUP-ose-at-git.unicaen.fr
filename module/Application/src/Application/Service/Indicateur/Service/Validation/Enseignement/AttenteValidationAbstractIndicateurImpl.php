<?php

namespace Application\Service\Indicateur\Service\Validation\Enseignement;

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
abstract class AttenteValidationAbstractIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s %s est en attente de validation de ses enseignements <em>%s</em>";
    protected $pluralTitlePattern   = "%s %s sont en attente de validation de leurs enseignements <em>%s</em>";
    
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
            ->join("int.service", "s")
            ->join("s.elementPedagogique", "ep")
            ->join("s.volumeHoraire", "vh")
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
            ->setParameter('tvh', $this->getTypeVolumeHoraire())
            ->andWhere("1 = pasHistorise(s)")
            ->andWhere("1 = pasHistorise(ep)")
            ->andWhere("1 = pasHistorise(vh)");
        
        /**
         * L'intervenant doit être à l'étape concernée dans le WF.
         */
        if ($this->findByWfEtapeCourante) {
            $service = $this->getServiceLocator()->get('ApplicationIntervenant'); /* @var $service Intervenant */
            $service->finderByWfEtapeCourante($this->getWorkflowStepKey(), $qb);
        }
        
        
        /**
         * Filtrage par type d'intervenant.
         */
        $qb
                ->andWhere("ti = :type")
                ->setParameter('type', $this->getTypeIntervenant());
        
        /**
         * Filtrage par structure d'intervention.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("ep.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        /**
         * Les volumes horaires ne doivent pas être validés.
         */
        $qb
            ->leftJoin("vh.validation", "val", Join::WITH, "1 = pasHistorise(val)")
            ->andWhere("val.id IS NULL");
        
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