<?php

namespace Enseignement\Form;

/**
 * Description of EnseignementSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EnseignementSaisieFormAwareTrait
{
    protected ?EnseignementSaisieForm $formServiceEnseignementSaisie = null;



    /**
     * @param EnseignementSaisieForm $formServiceEnseignementSaisie
     *
     * @return self
     */
    public function setFormServiceEnseignementSaisie(?EnseignementSaisieForm $formServiceEnseignementSaisie)
    {
        $this->formServiceEnseignementSaisie = $formServiceEnseignementSaisie;

        return $this;
    }



    public function getFormServiceEnseignementSaisie(): ?EnseignementSaisieForm
    {
        if (!empty($this->formServiceEnseignementSaisie)) {
            return $this->formServiceEnseignementSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EnseignementSaisieForm::class);
    }
}