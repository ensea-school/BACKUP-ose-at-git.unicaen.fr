<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraire;

/**
 * Description of FormuleResultatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraire
     */
    private $serviceFormuleResultatVolumeHoraire;



    /**
     * @param FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraire(FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire)
    {
        $this->serviceFormuleResultatVolumeHoraire = $serviceFormuleResultatVolumeHoraire;

        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraire
     */
    public function getServiceFormuleResultatVolumeHoraire()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraire)) {
            $this->serviceFormuleResultatVolumeHoraire = \Application::$container->get('ApplicationFormuleResultatVolumeHoraire');
        }

        return $this->serviceFormuleResultatVolumeHoraire;
    }
}