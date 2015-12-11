<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Traits;

use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of ElementCentreCoutFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementCentreCoutFieldsetAwareTrait
{
    /**
     * @var ElementCentreCoutFieldset
     */
    private $fieldsetOffreFormationEtapeCentreCoutElementCentreCout;





    /**
     * @param ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout
     * @return self
     */
    public function setFieldsetOffreFormationEtapeCentreCoutElementCentreCout( ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout )
    {
        $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout = $fieldsetOffreFormationEtapeCentreCoutElementCentreCout;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementCentreCoutFieldset
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationEtapeCentreCoutElementCentreCout()
    {
        if (!empty($this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout)){
            return $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout;
        }

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
        return $serviceLocator->get('FormElementManager')->get('ElementCentreCoutFieldset');
    }
}