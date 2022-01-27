<?php

namespace Application\Form\Voirie\Traits;


use Application\Form\Voirie\VoirieSaisieForm;

/**
 * Description of VoirieSaisieFormAwareTrait
 */
trait VoirieSaisieFormAwareTrait
{
    /**
     * @var VoirieSaisieForm
     */
    private $formVoirieSaisie;



    /**
     * @param VoirieSaisieForm $formVoirieSaisie
     *
     * @return self
     */
    public function setFormVoirieSaisie(VoirieSaisieForm $formVoirieSaisie)
    {
        $this->formVoirieSaisie = $formVoirieSaisie;

        return $this;
    }



    public function getFormVoirieSaisie(): VoirieSaisieForm
    {
        if (!empty($this->formVoirieSaisie)) {
            return $this->formVoirieSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(VoirieSaisieForm::class);
    }
}

