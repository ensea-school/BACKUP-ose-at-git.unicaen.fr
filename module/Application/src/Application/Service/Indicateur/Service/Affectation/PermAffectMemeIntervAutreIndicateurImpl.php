<?php

namespace Application\Service\Indicateur\Service\Affectation;

use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\StatutIntervenant as StatutIntervenantEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PermAffectMemeIntervAutreIndicateurImpl extends IntervAffectMemeIntervAutreAbstractIndicateurImpl//AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s permanent  affecté  dans ma structure (%s) a   des enseignements <em>%ss Validés</em> dans une autre structure";
    protected $pluralTitlePattern   = "%s permanents affectés dans ma structure (%s) ont des enseignements <em>%ss Validés</em> dans une autre structure";
    
    /**
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu();
        }
        
        return $this->typeVolumeHoraire;
    }
    
    /**
     * @return StatutIntervenantEntity
     */
    protected function getStatutIntervenant()
    {
        return null;
    }
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getStructure(), $this->getTypeVolumeHoraire());
        $this->pluralTitlePattern   = sprintf($this->pluralTitlePattern,   '%s', $this->getStructure(), $this->getTypeVolumeHoraire());
        
        return AbstractIntervenantResultIndicateurImpl::getTitle(false);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder()
                ->andWhere("ti.code = :type")
                ->setParameter('type', TypeIntervenant::CODE_PERMANENT);
         
        return $qb;
    }
}