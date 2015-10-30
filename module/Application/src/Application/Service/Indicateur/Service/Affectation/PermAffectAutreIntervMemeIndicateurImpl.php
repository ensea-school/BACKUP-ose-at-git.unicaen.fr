<?php

namespace Application\Service\Indicateur\Service\Affectation;

use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PermAffectAutreIntervMemeIndicateurImpl extends IntervAffectAutreIntervMemeAbstractIndicateurImpl
{
    protected $singularTitlePattern = "%s permanent  affecté  dans une autre structure a   des enseignements <em>%ss Validés</em> dans ma structure (%s)";
    protected $pluralTitlePattern   = "%s permanents affectés dans une autre structure ont des enseignements <em>%ss Validés</em> dans ma structure (%s)";


    public function getTypeVolumeHoraire()
    {
        if (!parent::getTypeVolumeHoraire()) {
            $sTvh = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
            /* @var $sTvh \Application\Service\TypeVolumeHoraire */
            $this->setTypeVolumeHoraire($sTvh->getPrevu());
        }

        return parent::getTypeVolumeHoraire();
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
        $this->singularTitlePattern = sprintf($this->singularTitlePattern, '%s', $this->getTypeVolumeHoraire(), $this->getStructure());
        $this->pluralTitlePattern   = sprintf($this->pluralTitlePattern,   '%s', $this->getTypeVolumeHoraire(), $this->getStructure());
        
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