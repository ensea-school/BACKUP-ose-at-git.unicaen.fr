<?php

namespace Lieu\Form;

use Lieu\Entity\Db\Structure;

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



    public function getFormStructureSaisie(?Structure $structure): ?StructureSaisieForm
    {
        if (!empty($this->formStructureSaisie)) {
            return $this->formStructureSaisie;
        }
        $form = \AppAdmin::container()->get('FormElementManager')->get(StructureSaisieForm::class);
        $form->setStructure($structure);
        $form->initCentreCout();
        $form->excludeStructure();

        return $form;
    }
}
