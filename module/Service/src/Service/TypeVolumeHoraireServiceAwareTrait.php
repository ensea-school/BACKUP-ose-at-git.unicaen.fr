<?php

namespace Service\Service;

/**
 * Description of TypeVolumeHoraireServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeVolumeHoraireServiceAwareTrait
{
    protected ?TypeVolumeHoraireService $serviceTypeVolumeHoraire = null;



    /**
     * @param TypeVolumeHoraireService $serviceTypeVolumeHoraire
     *
     * @return self
     */
    public function setServiceTypeVolumeHoraire(?TypeVolumeHoraireService $serviceTypeVolumeHoraire)
    {
        $this->serviceTypeVolumeHoraire = $serviceTypeVolumeHoraire;

        return $this;
    }



    public function getServiceTypeVolumeHoraire(): ?TypeVolumeHoraireService
    {
        if (empty($this->serviceTypeVolumeHoraire)) {
            $this->serviceTypeVolumeHoraire = \Framework\Application\Application::getInstance()->container()->get(TypeVolumeHoraireService::class);
        }

        return $this->serviceTypeVolumeHoraire;
    }
}