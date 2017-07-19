<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\ModulateurSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of ModulateurSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurSaisieFormAwareTrait
{
    /**
     * @var ModulateurSaisieForm
     */
    private $formModulateurSaisie;


    /**
     * @param ModulateurSaisieForm $formModulateurSaisie
     * @return self
     */
    public function setFormModulateurSaisie( ModulateurSaisieForm $formModulateurSaisie )
    {
        $this->formModulateurSaisie = $formModulateurSaisie;
        return $this;
    }


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModulateurSaisieForm
     * @throws RuntimeException
     */
    public function getFormModulateurSaisie()
    {
        if (!empty($this->formModulateurSaisie)){
            return $this->formModulateurSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('ModulateurSaisie');
    }
}

