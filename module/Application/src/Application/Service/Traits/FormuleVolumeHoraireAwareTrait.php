<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraire;
use Common\Exception\RuntimeException;

trait FormuleVolumeHoraireAwareTrait
{
    /**
     * description
     *
     * @var FormuleVolumeHoraire
     */
    private $serviceFormuleVolumeHoraire;

    /**
     *
     * @param FormuleVolumeHoraire $serviceFormuleVolumeHoraire
     * @return self
     */
    public function setServiceFormuleVolumeHoraire( FormuleVolumeHoraire $serviceFormuleVolumeHoraire )
    {
        $this->serviceFormuleVolumeHoraire = $serviceFormuleVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return FormuleVolumeHoraire
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleVolumeHoraire()
    {
        if (empty($this->serviceFormuleVolumeHoraire)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleVolumeHoraire');
        }else{
            return $this->serviceFormuleVolumeHoraire;
        }
    }

}