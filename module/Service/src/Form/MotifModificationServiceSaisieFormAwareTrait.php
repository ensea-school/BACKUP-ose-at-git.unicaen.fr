<?php

namespace Service\Form;

/**
 * Description of MotifModificationServiceSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceSaisieFormAwareTrait
{
    protected ?MotifModificationServiceSaisieForm $formMotifModificationServiceMotifModificationServiceSaisie = null;



    /**
     * @param MotifModificationServiceSaisieForm $formMotifModificationServiceMotifModificationServiceSaisie
     *
     * @return self
     */
    public function setFormMotifModificationServiceMotifModificationServiceSaisie(?MotifModificationServiceSaisieForm $formMotifModificationServiceMotifModificationServiceSaisie)
    {
        $this->formMotifModificationServiceMotifModificationServiceSaisie = $formMotifModificationServiceMotifModificationServiceSaisie;

        return $this;
    }



    public function getFormMotifModificationServiceMotifModificationServiceSaisie(): ?MotifModificationServiceSaisieForm
    {
        if (!empty($this->formMotifModificationServiceMotifModificationServiceSaisie)) {
            return $this->formMotifModificationServiceMotifModificationServiceSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MotifModificationServiceSaisieForm::class);
    }
}