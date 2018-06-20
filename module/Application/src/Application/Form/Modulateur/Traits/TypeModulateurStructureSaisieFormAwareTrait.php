<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\TypeModulateurStructureSaisieForm;

/**
 * Description of TypeModulateurStructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurStructureSaisieFormAwareTrait
{
    /**
     * @var TypeModulateurStructureSaisieForm
     */
    private $formTypeModulateurStructureSaisie;



    /**
     * @param TypeModulateurStrucureSaisieForm $formTypeModulateurStructureSaisie
     *
     * @return self
     */
    public function setFormTypeModulateurStructureSaisie(TypeModulateurStructureSaisieForm $formTypeModulateurStructureSaisie)
    {
        $this->formTypeModulateurStrucureSaisie = $formTypeModulateurStructureSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeModulateurStructureSaisieForm
     */
    public function getFormTypeModulateurStructureSaisie()
    {
        if (!empty($this->formTypeModulateurStructureSaisie)) {
            return $this->formTypeModulateurStructureSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeModulateurStructureSaisieForm::class);
    }
}

