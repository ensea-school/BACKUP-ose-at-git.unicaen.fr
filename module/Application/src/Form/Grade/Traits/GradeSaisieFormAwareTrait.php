<?php

namespace Application\Form\Grade\Traits;


use Application\Form\Grade\GradeSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait GradeSaisieFormAwareTrait
{
    /**
     * @var VoirieGradeForm
     */
    private $formGradeSaisie;



    /**
     * @param GradeSaisieForm $formGradeSaisie
     *
     * @return self
     */
    public function setFormGradeSaisie(VoirieSaisieForm $formGradeSaisie)
    {
        $this->formGradeSaisie = $formGradeSaisie;

        return $this;
    }



    public function getFormGradeSaisie(): GradeSaisieForm
    {
        if (!empty($this->formGradeSaisie)) {
            return $this->formGradeSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(GradeSaisieForm::class);
    }
}

