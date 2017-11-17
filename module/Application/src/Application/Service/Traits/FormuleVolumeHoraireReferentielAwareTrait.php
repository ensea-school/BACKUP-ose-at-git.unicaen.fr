<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraireReferentiel;

/**
 * Description of FormuleVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireReferentielAwareTrait
{
    /**
     * @var FormuleVolumeHoraireReferentiel
     */
    private $serviceFormuleVolumeHoraireReferentiel;



    /**
     * @param FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceFormuleVolumeHoraireReferentiel(FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel)
    {
        $this->serviceFormuleVolumeHoraireReferentiel = $serviceFormuleVolumeHoraireReferentiel;

        return $this;
    }



    /**
     * @return FormuleVolumeHoraireReferentiel
     */
    public function getServiceFormuleVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleVolumeHoraireReferentiel)) {
            $this->serviceFormuleVolumeHoraireReferentiel = \Application::$container->get('ApplicationFormuleVolumeHoraireReferentiel');
        }

        return $this->serviceFormuleVolumeHoraireReferentiel;
    }
}