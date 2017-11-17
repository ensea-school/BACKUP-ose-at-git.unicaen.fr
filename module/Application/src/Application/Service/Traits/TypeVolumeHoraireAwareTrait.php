<?php

namespace Application\Service\Traits;

use Application\Service\TypeVolumeHoraire;

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
    private $serviceTypeVolumeHoraire;



    /**
     * @param TypeVolumeHoraire $serviceTypeVolumeHoraire
     *
     * @return self
     */
    public function setServiceTypeVolumeHoraire(TypeVolumeHoraire $serviceTypeVolumeHoraire)
    {
        $this->serviceTypeVolumeHoraire = $serviceTypeVolumeHoraire;

        return $this;
    }



    /**
     * @return TypeVolumeHoraire
     */
    public function getServiceTypeVolumeHoraire()
    {
        if (empty($this->serviceTypeVolumeHoraire)) {
            $this->serviceTypeVolumeHoraire = \Application::$container->get('ApplicationTypeVolumeHoraire');
        }

        return $this->serviceTypeVolumeHoraire;
    }
}