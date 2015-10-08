<?php

namespace Application\Service\Traits;

use Application\Service\EtatVolumeHoraire;
use Application\Module;
use RuntimeException;

/**
 * Description of EtatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireAwareTrait
{
    /**
     * @var EtatVolumeHoraire
     */
    private $serviceEtatVolumeHoraire;





    /**
     * @param EtatVolumeHoraire $serviceEtatVolumeHoraire
     * @return self
     */
    public function setServiceEtatVolumeHoraire( EtatVolumeHoraire $serviceEtatVolumeHoraire )
    {
        $this->serviceEtatVolumeHoraire = $serviceEtatVolumeHoraire;
        return $this;
    }



    /**
     * @return EtatVolumeHoraire
     * @throws RuntimeException
     */
    public function getServiceEtatVolumeHoraire()
    {
        if (empty($this->serviceEtatVolumeHoraire)){
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
        $this->serviceEtatVolumeHoraire = $serviceLocator->get('ApplicationEtatVolumeHoraire');
        }
        return $this->serviceEtatVolumeHoraire;
    }
}