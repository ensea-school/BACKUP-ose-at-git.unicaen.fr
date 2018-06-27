<?php

namespace Application\Form\GroupeTypeFormation\Traits;

use Application\Form\GroupeTypeFormation\TypeFormationSaisieForm;

/**
 * Description of TypeFormationSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationSaisieFormAwareTrait
{
    /**
     * @var TypeFormationSaisieForm
     */
    private $formTypeFormationSaisie;



    /**
     * @param TypeFormationSaisieForm $formTypeFormationSaisie
     *
     * @return self
     */
    public function setFormTypeFormationSaisie(TypeFormationSaisieForm $formTypeFormationSaisie)
    {
        $this->formTypeFormationSaisie = $formTypeFormationSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeFormationSaisieForm
     */
    public function getFormTypeFormationSaisie()
    {
        if (!empty($this->formTypeFormationSaisie)) {
            return $this->formTypeFormationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeFormationSaisieForm::class);
    }
}
