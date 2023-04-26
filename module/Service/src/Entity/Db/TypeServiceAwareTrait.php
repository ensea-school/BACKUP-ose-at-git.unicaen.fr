<?php

namespace Service\Entity\Db;

/**
 * Description of TypeVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeServiceAwareTrait
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