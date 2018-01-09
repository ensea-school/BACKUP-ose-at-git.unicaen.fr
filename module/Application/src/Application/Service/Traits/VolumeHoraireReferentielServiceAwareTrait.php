<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireReferentielService;
use RuntimeException;

/**
 * Description of VolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireReferentielServiceAwareTrait
{
    /**
     * @var VolumeHoraireReferentielService
     */
    private $serviceVolumeHoraireReferentiel;



    /**
     * @param VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel(VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel)
    {
        $this->serviceVolumeHoraireReferentiel = $serviceVolumeHoraireReferentiel;

        return $this;
    }



    /**
     * @return VolumeHoraireReferentielService
     */
    public function getServiceVolumeHoraireReferentiel()
    {
        if (empty($this->serviceVolumeHoraireReferentiel)) {
            $this->serviceVolumeHoraireReferentiel = \Application::$container->get(VolumeHoraireReferentielService::class);
        }

        return $this->serviceVolumeHoraireReferentiel;
    }
}