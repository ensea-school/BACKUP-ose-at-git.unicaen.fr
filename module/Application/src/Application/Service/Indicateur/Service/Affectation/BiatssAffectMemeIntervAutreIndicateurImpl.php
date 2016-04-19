<?php

namespace Application\Service\Indicateur\Service\Affectation;

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
        if (null === $this->statutIntervenant) {
            $qb                      = $this->getServiceStatutIntervenant()->finderBySourceCode('BIATSS');
            $this->statutIntervenant = $qb->getQuery()->getOneOrNullResult();
        }

        return $this->statutIntervenant;
    }
}