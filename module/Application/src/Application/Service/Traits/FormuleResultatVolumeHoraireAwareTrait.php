<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraire;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleResultatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraire
     */
    private $serviceFormuleResultatVolumeHoraire;





    /**
     * @param FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraire( FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire )
    {
        $this->serviceFormuleResultatVolumeHoraire = $serviceFormuleResultatVolumeHoraire;
        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraire
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatVolumeHoraire()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraire)){
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
        $this->serviceFormuleResultatVolumeHoraire = $serviceLocator->get('ApplicationFormuleResultatVolumeHoraire');
        }
        return $this->serviceFormuleResultatVolumeHoraire;
    }
}