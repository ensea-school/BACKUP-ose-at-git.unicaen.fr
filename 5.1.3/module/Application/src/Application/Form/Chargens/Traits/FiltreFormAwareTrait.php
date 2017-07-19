<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\FiltreForm;
use Application\Module;
use RuntimeException;

/**
 * Description of FiltreFormAwareTrait
 *
 * @author UnicaenCode
 */
trait FiltreFormAwareTrait
{
    /**
     * @var FiltreForm
     */
    private $formChargensFiltre;





    /**
     * @param FiltreForm $formChargensFiltre
     * @return self
     */
    public function setFormChargensFiltre( FiltreForm $formChargensFiltre )
    {
        $this->formChargensFiltre = $formChargensFiltre;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return FiltreForm
     * @throws RuntimeException
     */
    public function getFormChargensFiltre()
    {
        if (!empty($this->formChargensFiltre)){
            return $this->formChargensFiltre;
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
        return $serviceLocator->get('FormElementManager')->get('ChargensFiltre');
    }
}