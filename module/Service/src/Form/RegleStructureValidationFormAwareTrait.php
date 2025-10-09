<?php

namespace Service\Form;

/**
 * Description of RegleStructureValidationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationFormAwareTrait
{
    protected ?RegleStructureValidationForm $formRegleStructureValidation = null;



    /**
     * @param RegleStructureValidationForm $formRegleStructureValidation
     *
     * @return self
     */
    public function setFormRegleStructureValidation(?RegleStructureValidationForm $formRegleStructureValidation)
    {
        $this->formRegleStructureValidation = $formRegleStructureValidation;

        return $this;
    }



    public function getFormRegleStructureValidation(): ?RegleStructureValidationForm
    {
        if (!empty($this->formRegleStructureValidation)) {
            return $this->formRegleStructureValidation;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(RegleStructureValidationForm::class);
    }
}