<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\TypeModulateurSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeModulateurSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurSaisieFormAwareTrait
{
    /**
     * @var TypeModulateurSaisieForm
     */
    private $formTypeModulateurSaisie;


    /**
     * @param TypeModulateurSaisieForm $formTypeModulateurSaisie
     * @return self
     */
    public function setFormTypeModulateurSaisie( TypeModulateurSaisieForm $formTypeModulateurSaisie )
    {
        $this->formTypeModulateurSaisie = $formTypeModulateurSaisie;
        return $this;
    }


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeModulateurSaisieForm
     * @throws RuntimeException
     */
    public function getFormTypeModulateurSaisie()
    {
        if (!empty($this->formTypeModulateurSaisie)){
            return $this->formTypeModulateurSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('TypeModulateurSaisie');
    }
}

