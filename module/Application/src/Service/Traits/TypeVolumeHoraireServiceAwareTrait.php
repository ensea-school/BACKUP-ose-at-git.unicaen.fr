<?php

namespace Application\Service\Traits;

use Application\Service\TypeVolumeHoraireService;

/**
 * Description of TypeVolumeHoraireServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeVolumeHoraireServiceAwareTrait
{
    protected ?TypeVolumeHoraireService $serviceTypeVolumeHoraire;



    /**
     * @param TypeVolumeHoraireService|null $serviceTypeVolumeHoraire
     *
     * @return self
     */
    public function setServiceTypeVolumeHoraire( ?TypeVolumeHoraireService $serviceTypeVolumeHoraire )
    {
        $this->serviceTypeVolumeHoraire = $serviceTypeVolumeHoraire;

        return $this;
    }



    public function getServiceTypeVolumeHoraire(): ?TypeVolumeHoraireService
    {
        if (!$this->serviceTypeVolumeHoraire){
            $this->serviceTypeVolumeHoraire = \Application::$container->get(TypeVolumeHoraireService::class);
        }

        return $this->serviceTypeVolumeHoraire;
    }
}