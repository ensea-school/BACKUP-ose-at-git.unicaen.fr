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
    protected ?StructureSaisieForm $formStructureStructureSaisie = null;



    /**
     * @param StructureSaisieForm $formStructureStructureSaisie
     *
     * @return self
     */
    public function setFormStructureStructureSaisie(?StructureSaisieForm $formStructureStructureSaisie)
    {
        $this->formStructureStructureSaisie = $formStructureStructureSaisie;

        return $this;
    }



    public function getFormStructureStructureSaisie(): ?StructureSaisieForm
    {
        if (!empty($this->formStructureStructureSaisie)) {
            return $this->formStructureStructureSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(StructureSaisieForm::class);
    }
}