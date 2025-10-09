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
        if (!empty($this->formStatutSaisie)) {
            return $this->formStatutSaisie;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(StatutSaisieForm::class);
    }
}