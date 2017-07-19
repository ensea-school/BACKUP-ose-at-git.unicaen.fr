<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireReferentiel;
use Application\Module;
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
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel( VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel )
    {
        $this->serviceVolumeHoraireReferentiel = $serviceVolumeHoraireReferentiel;
        return $this;
    }



    /**
     * @return VolumeHoraireReferentiel
     * @throws RuntimeException
     */
    public function getServiceVolumeHoraireReferentiel()
    {
        if (empty($this->serviceVolumeHoraireReferentiel)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceVolumeHoraireReferentiel = $serviceLocator->get('ApplicationVolumeHoraireReferentiel');
        }
        return $this->serviceVolumeHoraireReferentiel;
    }
}