<?php

namespace OffreFormation\Form\GroupeTypeFormation\Traits;

use OffreFormation\Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm;

/**
 * Description of GroupeTypeFormationSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationSaisieFormAwareTrait
{
    protected ?GroupeTypeFormationSaisieForm $formGroupeTypeFormationGroupeTypeFormationSaisie = null;



    /**
     * @param GroupeTypeFormationSaisieForm $formGroupeTypeFormationGroupeTypeFormationSaisie
     *
     * @return self
     */
    public function setFormGroupeTypeFormationGroupeTypeFormationSaisie(?GroupeTypeFormationSaisieForm $formGroupeTypeFormationGroupeTypeFormationSaisie)
    {
        $this->formGroupeTypeFormationGroupeTypeFormationSaisie = $formGroupeTypeFormationGroupeTypeFormationSaisie;

        return $this;
    }



    public function getFormGroupeTypeFormationGroupeTypeFormationSaisie(): ?GroupeTypeFormationSaisieForm
    {
        if (!empty($this->formGroupeTypeFormationGroupeTypeFormationSaisie)) {
            return $this->formGroupeTypeFormationGroupeTypeFormationSaisie;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(GroupeTypeFormationSaisieForm::class);
    }
}