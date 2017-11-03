<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireEnsService;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceVolumeHoraireEns( VolumeHoraireEnsService $serviceVolumeHoraireEns )
    {
        $this->serviceVolumeHoraireEns = $serviceVolumeHoraireEns;
        return $this;
    }



    /**
     * @return VolumeHoraireEnsService
     * @throws RuntimeException
     */
    public function getServiceVolumeHoraireEns()
    {
        if (empty($this->serviceVolumeHoraireEns)){
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
        $this->serviceVolumeHoraireEns = $serviceLocator->get('ApplicationVolumeHoraireEns');
        }
        return $this->serviceVolumeHoraireEns;
    }
}