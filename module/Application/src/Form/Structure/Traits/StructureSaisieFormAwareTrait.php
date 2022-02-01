<?php

namespace Application\Form\Structure\Traits;

use Application\Form\Structure\StructureSaisieForm;

/**
 * Description of StructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait StructureSaisieFormAwareTrait
{
    protected ?StructureSaisieForm $formStructureStructureSaisie;



    /**
     * @param StructureSaisieForm|null $formStructureStructureSaisie
     *
     * @return self
     */
    public function setFormStructureStructureSaisie( ?StructureSaisieForm $formStructureStructureSaisie )
    {
        $this->formStructureStructureSaisie = $formStructureStructureSaisie;

        return $this;
    }



    public function getFormStructureStructureSaisie(): ?StructureSaisieForm
    {
        if (!$this->formStructureStructureSaisie){
            $this->formStructureStructureSaisie = \Application::$container->get('FormElementManager')->get(StructureSaisieForm::class);
        }

        return $this->formStructureStructureSaisie;
    }
}