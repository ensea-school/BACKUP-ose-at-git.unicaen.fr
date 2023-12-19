<?php

namespace Lieu\Form;

/**
 * Description of StructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait StructureSaisieFormAwareTrait
{
    protected ?StructureSaisieForm $formStructureSaisie = null;



    /**
     * @param StructureSaisieForm $formStructureSaisie
     *
     * @return self
     */
    public function setFormStructureSaisie(?StructureSaisieForm $formStructureSaisie)
    {
        $this->formStructureSaisie = $formStructureSaisie;

        return $this;
    }



    public function getFormStructureSaisie(): ?StructureSaisieForm
    {
        if (!empty($this->formStructureSaisie)) {
            return $this->formStructureSaisie;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(StructureSaisieForm::class);
    }
}