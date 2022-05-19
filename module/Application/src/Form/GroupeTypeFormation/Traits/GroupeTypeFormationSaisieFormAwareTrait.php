<?php

namespace Application\Form\GroupeTypeFormation\Traits;

use Application\Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm;

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

        return \Application::$container->get('FormElementManager')->get(GroupeTypeFormationSaisieForm::class);
    }
}