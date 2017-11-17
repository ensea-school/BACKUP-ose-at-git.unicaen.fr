<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireReferentiel;
use RuntimeException;

/**
 * Description of VolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireReferentielAwareTrait
{
    /**
     * @var VolumeHoraireReferentiel
     */
    private $serviceVolumeHoraireReferentiel;



    /**
     * @param VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel(VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel)
    {
        $this->serviceVolumeHoraireReferentiel = $serviceVolumeHoraireReferentiel;

        return $this;
    }



    /**
     * @return VolumeHoraireReferentiel
     */
    public function getServiceVolumeHoraireReferentiel()
    {
        if (empty($this->serviceVolumeHoraireReferentiel)) {
            $this->serviceVolumeHoraireReferentiel = \Application::$container->get('ApplicationVolumeHoraireReferentiel');
        }

        return $this->serviceVolumeHoraireReferentiel;
    }
}