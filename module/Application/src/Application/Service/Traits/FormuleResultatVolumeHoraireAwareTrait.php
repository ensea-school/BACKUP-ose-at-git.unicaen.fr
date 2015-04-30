<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraire;
use Common\Exception\RuntimeException;

trait FormuleResultatVolumeHoraireAwareTrait
{
    /**
     * description
     *
     * @var FormuleResultatVolumeHoraire
     */
    private $serviceFormuleResultatVolumeHoraire;

    /**
     *
     * @param FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraire( FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire )
    {
        $this->serviceFormuleResultatVolumeHoraire = $serviceFormuleResultatVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return FormuleResultatVolumeHoraire
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleResultatVolumeHoraire()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraire)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleResultatVolumeHoraire');
        }else{
            return $this->serviceFormuleResultatVolumeHoraire;
        }
    }

}