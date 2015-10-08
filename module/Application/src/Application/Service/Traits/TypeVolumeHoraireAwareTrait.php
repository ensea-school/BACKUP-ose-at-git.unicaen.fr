<?php

namespace Application\Service\Traits;

use Application\Service\TypeVolumeHoraire;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeVolumeHoraireAwareTrait
{
    /**
     * @var TypeVolumeHoraire
     */
    private $serviceTypeVolumeHoraire;





    /**
     * @param TypeVolumeHoraire $serviceTypeVolumeHoraire
     * @return self
     */
    public function setServiceTypeVolumeHoraire( TypeVolumeHoraire $serviceTypeVolumeHoraire )
    {
        $this->serviceTypeVolumeHoraire = $serviceTypeVolumeHoraire;
        return $this;
    }



    /**
     * @return TypeVolumeHoraire
     * @throws RuntimeException
     */
    public function getServiceTypeVolumeHoraire()
    {
        if (empty($this->serviceTypeVolumeHoraire)){
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
        $this->serviceTypeVolumeHoraire = $serviceLocator->get('ApplicationTypeVolumeHoraire');
        }
        return $this->serviceTypeVolumeHoraire;
    }
}