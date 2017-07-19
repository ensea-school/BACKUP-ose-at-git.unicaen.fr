<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraire;
use Application\Module;
use RuntimeException;

/**
 * Description of VolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireAwareTrait
{
    /**
     * @var VolumeHoraire
     */
    private $serviceVolumeHoraire;





    /**
     * @param VolumeHoraire $serviceVolumeHoraire
     * @return self
     */
    public function setServiceVolumeHoraire( VolumeHoraire $serviceVolumeHoraire )
    {
        $this->serviceVolumeHoraire = $serviceVolumeHoraire;
        return $this;
    }



    /**
     * @return VolumeHoraire
     * @throws RuntimeException
     */
    public function getServiceVolumeHoraire()
    {
        if (empty($this->serviceVolumeHoraire)){
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
        $this->serviceVolumeHoraire = $serviceLocator->get('ApplicationVolumeHoraire');
        }
        return $this->serviceVolumeHoraire;
    }
}