<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireEnsService;

/**
 * Description of VolumeHoraireEnsAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireEnsServiceAwareTrait
{
    /**
     * @var VolumeHoraireEnsService
     */
    private $serviceVolumeHoraireEns;



    /**
     * @param VolumeHoraireEnsService $serviceVolumeHoraireEns
     *
     * @return self
     */
    public function setServiceVolumeHoraireEns(VolumeHoraireEnsService $serviceVolumeHoraireEns)
    {
        $this->serviceVolumeHoraireEns = $serviceVolumeHoraireEns;

        return $this;
    }



    /**
     * @return VolumeHoraireEnsService
     */
    public function getServiceVolumeHoraireEns()
    {
        if (empty($this->serviceVolumeHoraireEns)) {
            $this->serviceVolumeHoraireEns = \Application::$container->get(VolumeHoraireEnsService::class);
        }

        return $this->serviceVolumeHoraireEns;
    }
}