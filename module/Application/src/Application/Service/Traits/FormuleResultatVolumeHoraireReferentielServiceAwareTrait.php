<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireReferentielService;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielServiceAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraireReferentielService
     */
    private $serviceFormuleResultatVolumeHoraireReferentiel;



    /**
     * @param FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel(FormuleResultatVolumeHoraireReferentielService $serviceFormuleResultatVolumeHoraireReferentiel)
    {
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceFormuleResultatVolumeHoraireReferentiel;

        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraireReferentielService
     */
    public function getServiceFormuleResultatVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraireReferentiel)) {
            $this->serviceFormuleResultatVolumeHoraireReferentiel = \Application::$container->get(FormuleResultatVolumeHoraireReferentielService::class);
        }

        return $this->serviceFormuleResultatVolumeHoraireReferentiel;
    }
}