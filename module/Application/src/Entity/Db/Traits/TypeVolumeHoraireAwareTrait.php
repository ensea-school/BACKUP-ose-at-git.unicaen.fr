<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeVolumeHoraire;

/**
 * Description of TypeVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeVolumeHoraireAwareTrait
{
    protected ?TypeVolumeHoraire $typeVolumeHoraire;



    /**
     * @param TypeVolumeHoraire|null $typeVolumeHoraire
     *
     * @return self
     */
    public function setTypeVolumeHoraire( ?TypeVolumeHoraire $typeVolumeHoraire )
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }



    public function getTypeVolumeHoraire(): ?TypeVolumeHoraire
    {
        return $this->typeVolumeHoraire;
    }
}