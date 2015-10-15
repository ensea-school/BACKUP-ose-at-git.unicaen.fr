<?php

namespace Application\Form\VolumeHoraire\Traits;

use Application\Form\VolumeHoraire\SaisieMultipleFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of SaisieMultipleFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieMultipleFieldsetAwareTrait
{
    /**
     * @var SaisieMultipleFieldset
     */
    private $fieldsetVolumeHoraireSaisieMultiple;





    /**
     * @param SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple
     * @return self
     */
    public function setFieldsetVolumeHoraireSaisieMultiple( SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple )
    {
        $this->fieldsetVolumeHoraireSaisieMultiple = $fieldsetVolumeHoraireSaisieMultiple;
        return $this;
    }



    /**
     * @return SaisieMultipleFieldset
     * @throws RuntimeException
     */
    public function getFieldsetVolumeHoraireSaisieMultiple()
    {
        if (empty($this->fieldsetVolumeHoraireSaisieMultiple)){
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
            $this->fieldsetVolumeHoraireSaisieMultiple = $serviceLocator->get('FormElementManager')->get('VolumeHoraireSaisieMultipleFieldset');
        }
        return $this->fieldsetVolumeHoraireSaisieMultiple;
    }
}