<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireService;

/**
 * Description of FormuleResultatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireServiceAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraireService
     */
    private $serviceFormuleResultatVolumeHoraire;



    /**
     * @param FormuleResultatVolumeHoraireService $serviceFormuleResultatVolumeHoraire
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraire(FormuleResultatVolumeHoraireService $serviceFormuleResultatVolumeHoraire)
    {
        $this->serviceFormuleResultatVolumeHoraire = $serviceFormuleResultatVolumeHoraire;

        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraireService
     */
    public function getServiceFormuleResultatVolumeHoraire()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraire)) {
            $this->serviceFormuleResultatVolumeHoraire = \Application::$container->get(FormuleResultatVolumeHoraireService::class);
        }

        return $this->serviceFormuleResultatVolumeHoraire;
    }
}