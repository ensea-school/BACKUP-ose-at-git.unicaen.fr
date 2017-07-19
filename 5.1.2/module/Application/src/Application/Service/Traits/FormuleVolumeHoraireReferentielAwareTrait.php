<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraireReferentiel;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireReferentielAwareTrait
{
    /**
     * @var FormuleVolumeHoraireReferentiel
     */
    private $serviceFormuleVolumeHoraireReferentiel;





    /**
     * @param FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceFormuleVolumeHoraireReferentiel( FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel )
    {
        $this->serviceFormuleVolumeHoraireReferentiel = $serviceFormuleVolumeHoraireReferentiel;
        return $this;
    }



    /**
     * @return FormuleVolumeHoraireReferentiel
     * @throws RuntimeException
     */
    public function getServiceFormuleVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleVolumeHoraireReferentiel)){
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
        $this->serviceFormuleVolumeHoraireReferentiel = $serviceLocator->get('ApplicationFormuleVolumeHoraireReferentiel');
        }
        return $this->serviceFormuleVolumeHoraireReferentiel;
    }
}