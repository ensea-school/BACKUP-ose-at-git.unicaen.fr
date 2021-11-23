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
    /**
     * @var RegleStructureValidationForm
     */
    private $formRegleStructureValidationSaisie;



    /**
     * @param RegleStructureValidationForm $formRegleStructureValidationSaisie
     *
     * @return self
     */
    public function setFormRegleStructureValidationSaisie(RegleStructureValidationForm $formRegleStructureValidationSaisie)
    {
        $this->formRegleStructureValidationSaisie = $formRegleStructureValidationSaisie;

        return $this;
    }



    /**
     *
     * @return RegleStructureValidationForm
     */
    public function getFormRegleStructureValidationSaisie()
    {
        if (!empty($this->formRegleStructureValidationSaisie)) {
            return $this->formRegleStructureValidationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(RegleStructureValidationForm::class);
    }
}
