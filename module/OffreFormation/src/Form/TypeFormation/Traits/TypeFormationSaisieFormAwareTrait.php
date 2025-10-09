<?php

namespace OffreFormation\Form\TypeFormation\Traits;

use OffreFormation\Form\TypeFormation\TypeFormationSaisieForm;

/**
 * Description of TypeFormationSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationSaisieFormAwareTrait
{
    protected ?TypeFormationSaisieForm $formTypeFormationTypeFormationSaisie = null;



    /**
     * @param TypeFormationSaisieForm $formTypeFormationTypeFormationSaisie
     *
     * @return self
     */
    public function setFormTypeFormationTypeFormationSaisie(?TypeFormationSaisieForm $formTypeFormationTypeFormationSaisie)
    {
        $this->formTypeFormationTypeFormationSaisie = $formTypeFormationTypeFormationSaisie;

        return $this;
    }



    public function getFormTypeFormationTypeFormationSaisie(): ?TypeFormationSaisieForm
    {
        if (!empty($this->formTypeFormationTypeFormationSaisie)) {
            return $this->formTypeFormationTypeFormationSaisie;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeFormationSaisieForm::class);
    }
}