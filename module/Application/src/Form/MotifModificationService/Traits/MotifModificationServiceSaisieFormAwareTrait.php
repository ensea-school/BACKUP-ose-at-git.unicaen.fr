<?php

namespace Application\Form\MotifModificationService\Traits;

use Application\Form\MotifModificationService\MotifModificationServiceSaisieForm;

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
    public function setFormMotifModificationServiceMotifModificationServiceSaisie( ?MotifModificationServiceSaisieForm $formMotifModificationServiceMotifModificationServiceSaisie )
    {
        $this->formMotifModificationServiceMotifModificationServiceSaisie = $formMotifModificationServiceMotifModificationServiceSaisie;

        return $this;
    }



    public function getFormMotifModificationServiceMotifModificationServiceSaisie(): ?MotifModificationServiceSaisieForm
    {
        if (empty($this->formMotifModificationServiceMotifModificationServiceSaisie)){
            $this->formMotifModificationServiceMotifModificationServiceSaisie = \Application::$container->get('FormElementManager')->get(MotifModificationServiceSaisieForm::class);
        }

        return $this->formMotifModificationServiceMotifModificationServiceSaisie;
    }
}