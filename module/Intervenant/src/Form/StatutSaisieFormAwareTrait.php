<?php

namespace Intervenant\Form;


/**
 * Description of StatutSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutSaisieFormAwareTrait
{
    protected ?StatutSaisieForm $formStatutSaisie = null;



    /**
     * @param StatutSaisieForm $formStatutSaisie
     *
     * @return self
     */
    public function setFormStatutSaisie(?StatutSaisieForm $formStatutSaisie)
    {
        $this->formStatutSaisie = $formStatutSaisie;

        return $this;
    }



    public function getFormStatutSaisie(): ?StatutSaisieForm
    {
        if (empty($this->formStatutSaisie)) {
            $this->formStatutSaisie = \Application::$container->get('FormElementManager')->get(StatutSaisieForm::class);
        }

        return $this->formStatutSaisie;
    }
}