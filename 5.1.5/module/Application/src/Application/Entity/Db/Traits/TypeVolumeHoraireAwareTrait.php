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
    /**
     * @var TypeVolumeHoraire
     */
    private $typeVolumeHoraire;





    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @return self
     */
    public function setTypeVolumeHoraire( TypeVolumeHoraire $typeVolumeHoraire = null )
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        return $this;
    }



    /**
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }
}