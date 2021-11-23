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
    /**
     * @var StructureSaisieForm
     */
    private $formStructureSaisie;



    /**
     * @param StructureSaisieForm $formStructureSaisie
     *
     * @return self
     */
    public function setFormStructureSaisie(StructureSaisieForm $formStructureSaisie)
    {
        $this->formStructureSaisie = $formStructureSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return StructureSaisieForm
     */
    public function getFormStructureSaisie()
    {
        if (!empty($this->formStructureSaisie)) {
            return $this->formStructureSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(StructureSaisieForm::class);
    }
}

