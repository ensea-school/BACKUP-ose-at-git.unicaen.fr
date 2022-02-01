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
    protected ?TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie;



    /**
     * @param TypeModulateurStructureSaisieForm|null $formModulateurTypeModulateurStructureSaisie
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
        if (!$this->formModulateurTypeModulateurStructureSaisie){
            $this->formModulateurTypeModulateurStructureSaisie = \Application::$container->get('FormElementManager')->get(TypeModulateurStructureSaisieForm::class);
        }

        return $this->formModulateurTypeModulateurStructureSaisie;
    }
}