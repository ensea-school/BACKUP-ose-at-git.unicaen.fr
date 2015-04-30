<?php

namespace Application\Service\Traits;

use Application\Service\EtatVolumeHoraire;
use Common\Exception\RuntimeException;

trait EtatVolumeHoraireAwareTrait
{
    /**
     * description
     *
     * @var EtatVolumeHoraire
     */
    private $serviceEtatVolumeHoraire;

    /**
     *
     * @param EtatVolumeHoraire $serviceEtatVolumeHoraire
     * @return self
     */
    public function setServiceEtatVolumeHoraire( EtatVolumeHoraire $serviceEtatVolumeHoraire )
    {
        $this->serviceEtatVolumeHoraire = $serviceEtatVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return EtatVolumeHoraire
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceEtatVolumeHoraire()
    {
        if (empty($this->serviceEtatVolumeHoraire)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationEtatVolumeHoraire');
        }else{
            return $this->serviceEtatVolumeHoraire;
        }
    }

}