<?php

namespace Intervenant\Form;

/**
 * Description of GradeSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait GradeSaisieFormAwareTrait
{
    protected ?GradeSaisieForm $formGradeGradeSaisie = null;



    /**
     * @param GradeSaisieForm $formGradeGradeSaisie
     *
     * @return self
     */
    public function setFormGradeGradeSaisie(?GradeSaisieForm $formGradeGradeSaisie)
    {
        $this->formGradeGradeSaisie = $formGradeGradeSaisie;

        return $this;
    }



    public function getFormGradeGradeSaisie(): ?GradeSaisieForm
    {
        if (!empty($this->formGradeGradeSaisie)) {
            return $this->formGradeGradeSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(GradeSaisieForm::class);
    }
}