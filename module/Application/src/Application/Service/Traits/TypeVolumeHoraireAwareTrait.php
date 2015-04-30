<?php

namespace Application\Service\Traits;

use Application\Service\TypeVolumeHoraire;
use Common\Exception\RuntimeException;

trait TypeVolumeHoraireAwareTrait
{
    /**
     * description
     *
     * @var TypeVolumeHoraire
     */
    private $serviceTypeVolumeHoraire;

    /**
     *
     * @param TypeVolumeHoraire $serviceTypeVolumeHoraire
     * @return self
     */
    public function setServiceTypeVolumeHoraire( TypeVolumeHoraire $serviceTypeVolumeHoraire )
    {
        $this->serviceTypeVolumeHoraire = $serviceTypeVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return TypeVolumeHoraire
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeVolumeHoraire()
    {
        if (empty($this->serviceTypeVolumeHoraire)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeVolumeHoraire');
        }else{
            return $this->serviceTypeVolumeHoraire;
        }
    }

}