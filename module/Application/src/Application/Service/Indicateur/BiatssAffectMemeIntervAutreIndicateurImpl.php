<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\StatutIntervenant;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class BiatssAffectMemeIntervAutreIndicateurImpl extends IntervAffectMemeIntervAutreAbstractIndicateurImpl
{
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
        if (null === $this->statutIntervenant) {
            $qb = $this->getServiceStatutIntervenant()->finderBySourceCode(StatutIntervenant::BIATSS);
            $this->statutIntervenant = $qb->getQuery()->getOneOrNullResult();
        }
        
        return $this->statutIntervenant;
    }
}