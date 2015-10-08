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
     * @return ElementCentreCoutFieldset
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationEtapeCentreCoutElementCentreCout()
    {
        if (empty($this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout)){
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
            $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout = $serviceLocator->getServiceLocator('FormElementManager')->get('ElementCentreCoutFieldset');
        }
        return $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout;
    }
}