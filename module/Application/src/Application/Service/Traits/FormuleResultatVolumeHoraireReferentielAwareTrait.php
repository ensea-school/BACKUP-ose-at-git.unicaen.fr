<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireReferentiel;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraireReferentiel
     */
    private $serviceFormuleResultatVolumeHoraireReferentiel;





    /**
     * @param FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel( FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel )
    {
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceFormuleResultatVolumeHoraireReferentiel;
        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraireReferentiel
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraireReferentiel)){
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
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceLocator->get('ApplicationFormuleResultatVolumeHoraireReferentiel');
        }
        return $this->serviceFormuleResultatVolumeHoraireReferentiel;
    }
}