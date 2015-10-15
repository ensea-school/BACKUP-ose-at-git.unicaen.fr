<?php

namespace Application\Form\VolumeHoraireReferentiel\Traits;

use Application\Form\VolumeHoraireReferentiel\SaisieMultipleFieldset;
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
    private $fieldsetVolumeHoraireReferentielSaisieMultiple;





    /**
     * @param SaisieMultipleFieldset $fieldsetVolumeHoraireReferentielSaisieMultiple
     * @return self
     */
    public function setFieldsetVolumeHoraireReferentielSaisieMultiple( SaisieMultipleFieldset $fieldsetVolumeHoraireReferentielSaisieMultiple )
    {
        $this->fieldsetVolumeHoraireReferentielSaisieMultiple = $fieldsetVolumeHoraireReferentielSaisieMultiple;
        return $this;
    }



    /**
     * @return SaisieMultipleFieldset
     * @throws RuntimeException
     */
    public function getFieldsetVolumeHoraireReferentielSaisieMultiple()
    {
        if (empty($this->fieldsetVolumeHoraireReferentielSaisieMultiple)){
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
            $this->fieldsetVolumeHoraireReferentielSaisieMultiple = $serviceLocator->get('FormElementManager')->get('VolumeHoraireReferentielSaisieMultipleFieldset');
        }
        return $this->fieldsetVolumeHoraireReferentielSaisieMultiple;
    }
}