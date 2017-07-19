<?php

namespace Application\Form\Traits;

use Application\Form\ParametresForm;
use Application\Module;
use RuntimeException;

/**
 * Description of ParametresFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresFormAwareTrait
{
    /**
     * @var ParametresForm
     */
    private $formParametres;





    /**
     * @param ParametresForm $formParametres
     * @return self
     */
    public function setFormParametres( ParametresForm $formParametres )
    {
        $this->formParametres = $formParametres;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ParametresForm
     * @throws RuntimeException
     */
    public function getFormParametres()
    {
        if (!empty($this->formParametres)){
            return $this->formParametres;
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
        return $serviceLocator->get('FormElementManager')->get('Parametres');
    }
}