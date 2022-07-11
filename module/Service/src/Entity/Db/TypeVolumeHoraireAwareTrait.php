<?php

namespace Application\Entity\Db\Traits;

use Service\Entity\Db\TypeVolumeHoraire;

/**
 * Description of TypeVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeVolumeHoraireAwareTrait
{
    protected ?TypeVolumeHoraire $typeVolumeHoraire = null;



    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return self
     */
    public function setTypeVolumeHoraire(?TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }



    public function getTypeVolumeHoraire(): ?TypeVolumeHoraire
    {
        return $this->typeVolumeHoraire;
    }
}