<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireReferentielService;

/**
 * Description of FormuleResultatVolumeHoraireReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielServiceAwareTrait
{
    protected ?FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel = null;



    /**
     * @param FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel(?FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel)
    {
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceFormuleResultatVolumeHoraireReferentiel;

        return $this;
    }



    public function getServiceFormuleResultatVolumeHoraireReferentiel(): ?FormuleResultatVolumeHoraireReferentielService
    {
        if (empty($this->serviceFormuleResultatVolumeHoraireReferentiel)) {
            $this->serviceFormuleResultatVolumeHoraireReferentiel = \Application::$container->get(FormuleResultatVolumeHoraireReferentielService::class);
        }

        return $this->serviceFormuleResultatVolumeHoraireReferentiel;
    }
}