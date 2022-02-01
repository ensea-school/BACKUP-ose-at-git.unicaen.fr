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
    protected ?TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie = null;



    /**
     * @param TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie
     *
     * @return self
     */
    public function setFormModulateurTypeModulateurStructureSaisie( ?TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie )
    {
        $this->formModulateurTypeModulateurStructureSaisie = $formModulateurTypeModulateurStructureSaisie;

        return $this;
    }



    public function getFormModulateurTypeModulateurStructureSaisie(): ?TypeModulateurStructureSaisieForm
    {
        if (empty($this->formModulateurTypeModulateurStructureSaisie)){
            $this->formModulateurTypeModulateurStructureSaisie = \Application::$container->get('FormElementManager')->get(TypeModulateurStructureSaisieForm::class);
        }

        return $this->formModulateurTypeModulateurStructureSaisie;
    }
}