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
    protected ?FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel;



    /**
     * @param FormuleResultatVolumeHoraireReferentielService|null $serviceFormuleResultatVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel( ?FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel )
    {
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceFormuleResultatVolumeHoraireReferentiel;

        return $this;
    }



    public function getServiceFormuleResultatVolumeHoraireReferentiel(): ?FormuleResultatVolumeHoraireReferentielService
    {
        if (!$this->serviceFormuleResultatVolumeHoraireReferentiel){
            $this->serviceFormuleResultatVolumeHoraireReferentiel = \Application::$container->get('FormElementManager')->get(FormuleResultatVolumeHoraireReferentielService::class);
        }

        return $this->serviceFormuleResultatVolumeHoraireReferentiel;
    }
}