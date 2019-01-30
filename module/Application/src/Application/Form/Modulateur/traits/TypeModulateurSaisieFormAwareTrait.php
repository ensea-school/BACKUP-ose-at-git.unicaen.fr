<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\TypeModulateurSaisieForm;

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
     *
     * @return self
     */
    public function setFormTypeModulateurSaisie(TypeModulateurSaisieForm $formTypeModulateurSaisie)
    {
        $this->formTypeModulateurSaisie = $formTypeModulateurSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeModulateurSaisieForm
     */
    public function getFormTypeModulateurSaisie()
    {
        if (!empty($this->formTypeModulateurSaisie)) {
            return $this->formTypeModulateurSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeModulateurSaisieForm::class);
    }
}

