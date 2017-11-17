<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\ModulateurSaisieForm;

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
     *
     * @return self
     */
    public function setFormModulateurSaisie(ModulateurSaisieForm $formModulateurSaisie)
    {
        $this->formModulateurSaisie = $formModulateurSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModulateurSaisieForm
     */
    public function getFormModulateurSaisie()
    {
        if (!empty($this->formModulateurSaisie)) {
            return $this->formModulateurSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('ModulateurSaisie');
    }
}

