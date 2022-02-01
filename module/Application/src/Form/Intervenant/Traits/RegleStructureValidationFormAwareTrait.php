<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\RegleStructureValidationForm;

/**
 * Description of RegleStructureValidationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationFormAwareTrait
{
    protected ?RegleStructureValidationForm $formIntervenantRegleStructureValidation = null;



    /**
     * @param RegleStructureValidationForm $formIntervenantRegleStructureValidation
     *
     * @return self
     */
    public function setFormIntervenantRegleStructureValidation( RegleStructureValidationForm $formIntervenantRegleStructureValidation )
    {
        $this->formIntervenantRegleStructureValidation = $formIntervenantRegleStructureValidation;

        return $this;
    }



    public function getFormIntervenantRegleStructureValidation(): ?RegleStructureValidationForm
    {
        if (empty($this->formIntervenantRegleStructureValidation)){
            $this->formIntervenantRegleStructureValidation = \Application::$container->get('FormElementManager')->get(RegleStructureValidationForm::class);
        }

        return $this->formIntervenantRegleStructureValidation;
    }
}