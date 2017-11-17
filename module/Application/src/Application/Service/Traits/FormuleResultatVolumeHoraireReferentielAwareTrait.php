<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireReferentiel;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraireReferentiel
     */
    private $serviceFormuleResultatVolumeHoraireReferentiel;



    /**
     * @param FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel(FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel)
    {
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceFormuleResultatVolumeHoraireReferentiel;

        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraireReferentiel
     */
    public function getServiceFormuleResultatVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraireReferentiel)) {
            $this->serviceFormuleResultatVolumeHoraireReferentiel = \Application::$container->get('ApplicationFormuleResultatVolumeHoraireReferentiel');
        }

        return $this->serviceFormuleResultatVolumeHoraireReferentiel;
    }
}