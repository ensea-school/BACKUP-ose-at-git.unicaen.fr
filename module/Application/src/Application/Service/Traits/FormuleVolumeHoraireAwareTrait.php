<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraire;

/**
 * Description of FormuleVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireAwareTrait
{
    /**
     * @var FormuleVolumeHoraire
     */
    private $serviceFormuleVolumeHoraire;



    /**
     * @param FormuleVolumeHoraire $serviceFormuleVolumeHoraire
     *
     * @return self
     */
    public function setServiceFormuleVolumeHoraire(FormuleVolumeHoraire $serviceFormuleVolumeHoraire)
    {
        $this->serviceFormuleVolumeHoraire = $serviceFormuleVolumeHoraire;

        return $this;
    }



    /**
     * @return FormuleVolumeHoraire
     */
    public function getServiceFormuleVolumeHoraire()
    {
        if (empty($this->serviceFormuleVolumeHoraire)) {
            $this->serviceFormuleVolumeHoraire = \Application::$container->get('ApplicationFormuleVolumeHoraire');
        }

        return $this->serviceFormuleVolumeHoraire;
    }
}