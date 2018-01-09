<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraireReferentielService;

/**
 * Description of FormuleVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireReferentielServiceAwareTrait
{
    /**
     * @var FormuleVolumeHoraireReferentielService
     */
    private $serviceFormuleVolumeHoraireReferentiel;



    /**
     * @param FormuleVolumeHoraireReferentielService $serviceFormuleVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceFormuleVolumeHoraireReferentiel(FormuleVolumeHoraireReferentielService $serviceFormuleVolumeHoraireReferentiel)
    {
        $this->serviceFormuleVolumeHoraireReferentiel = $serviceFormuleVolumeHoraireReferentiel;

        return $this;
    }



    /**
     * @return FormuleVolumeHoraireReferentielService
     */
    public function getServiceFormuleVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleVolumeHoraireReferentiel)) {
            $this->serviceFormuleVolumeHoraireReferentiel = \Application::$container->get(FormuleVolumeHoraireReferentielService::class);
        }

        return $this->serviceFormuleVolumeHoraireReferentiel;
    }
}