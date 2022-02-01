<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireService;

/**
 * Description of FormuleResultatVolumeHoraireServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireServiceAwareTrait
{
    protected ?FormuleResultatVolumeHoraireService $serviceFormuleResultatVolumeHoraire;



    /**
     * @param FormuleResultatVolumeHoraireService|null $serviceFormuleResultatVolumeHoraire
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraire( ?FormuleResultatVolumeHoraireService $serviceFormuleResultatVolumeHoraire )
    {
        $this->serviceFormuleResultatVolumeHoraire = $serviceFormuleResultatVolumeHoraire;

        return $this;
    }



    public function getServiceFormuleResultatVolumeHoraire(): ?FormuleResultatVolumeHoraireService
    {
        if (!$this->serviceFormuleResultatVolumeHoraire){
            $this->serviceFormuleResultatVolumeHoraire = \Application::$container->get('FormElementManager')->get(FormuleResultatVolumeHoraireService::class);
        }

        return $this->serviceFormuleResultatVolumeHoraire;
    }
}