<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraire;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireAwareTrait
{
    /**
     * @var FormuleVolumeHoraire
     */
    private $serviceFormuleVolumeHoraire;





    /**
     * @param FormuleVolumeHoraire $serviceFormuleVolumeHoraire
     * @return self
     */
    public function setServiceFormuleVolumeHoraire( FormuleVolumeHoraire $serviceFormuleVolumeHoraire )
    {
        $this->serviceFormuleVolumeHoraire = $serviceFormuleVolumeHoraire;
        return $this;
    }



    /**
     * @return FormuleVolumeHoraire
     * @throws RuntimeException
     */
    public function getServiceFormuleVolumeHoraire()
    {
        if (empty($this->serviceFormuleVolumeHoraire)){
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
        $this->serviceFormuleVolumeHoraire = $serviceLocator->get('ApplicationFormuleVolumeHoraire');
        }
        return $this->serviceFormuleVolumeHoraire;
    }
}