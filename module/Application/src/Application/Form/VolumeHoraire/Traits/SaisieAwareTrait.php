<?php

namespace Application\Form\VolumeHoraire\Traits;

use Application\Form\VolumeHoraire\Saisie;
use Application\Module;
use RuntimeException;

/**
 * Description of SaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieAwareTrait
{
    /**
     * @var Saisie
     */
    private $formVolumeHoraireSaisie;





    /**
     * @param Saisie $formVolumeHoraireSaisie
     * @return self
     */
    public function setFormVolumeHoraireSaisie( Saisie $formVolumeHoraireSaisie )
    {
        $this->formVolumeHoraireSaisie = $formVolumeHoraireSaisie;
        return $this;
    }



    /**
     * @return Saisie
     * @throws RuntimeException
     */
    public function getFormVolumeHoraireSaisie()
    {
        if (empty($this->formVolumeHoraireSaisie)){
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
            $this->formVolumeHoraireSaisie = $serviceLocator->getServiceLocator('FormElementManager')->get('VolumeHoraireSaisie');
        }
        return $this->formVolumeHoraireSaisie;
    }
}