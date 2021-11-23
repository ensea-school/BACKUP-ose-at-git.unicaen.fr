<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeVolumeHoraire;

/**
 * Description of TypeVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeVolumeHoraireAwareInterface
{
    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @return self
     */
    public function setTypeVolumeHoraire( TypeVolumeHoraire $typeVolumeHoraire = null );



    /**
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire();
}