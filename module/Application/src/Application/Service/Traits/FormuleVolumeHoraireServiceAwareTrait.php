<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraireService;

/**
 * Description of FormuleVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireServiceAwareTrait
{
    /**
     * @var FormuleVolumeHoraireService
     */
    private $serviceFormuleVolumeHoraire;



    /**
     * @param FormuleVolumeHoraireService $serviceFormuleVolumeHoraire
     *
     * @return self
     */
    public function setServiceFormuleVolumeHoraire(FormuleVolumeHoraireService $serviceFormuleVolumeHoraire)
    {
        $this->serviceFormuleVolumeHoraire = $serviceFormuleVolumeHoraire;

        return $this;
    }



    /**
     * @return FormuleVolumeHoraireService
     */
    public function getServiceFormuleVolumeHoraire()
    {
        if (empty($this->serviceFormuleVolumeHoraire)) {
            $this->serviceFormuleVolumeHoraire = \Application::$container->get(FormuleVolumeHoraireService::class);
        }

        return $this->serviceFormuleVolumeHoraire;
    }
}