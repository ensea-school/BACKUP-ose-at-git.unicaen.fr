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
    /**
     * @var GroupeTypeFormationSaisieForm
     */
    private $formGroupeTypeFormationSaisie;



    /**
     * @param GroupeTypeFormationSaisieForm $formGroupeTypeFormationSaisie
     *
     * @return self
     */
    public function setFormGroupeTypeFormationSaisie(GroupeTypeFormationSaisieForm $formGroupeTypeFormationSaisie)
    {
        $this->formGroupeTypeFormationSaisie = $formGroupeTypeFormationSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return GroupeTypeFormationSaisieForm
     */
    public function getFormGroupeTypeFormationSaisie()
    {
        if (!empty($this->formGroupeTypeFormationSaisie)) {
            return $this->formGroupeTypeFormationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(GroupeTypeFormationSaisieForm::class);
    }
}
