<?php

namespace Application\Form\Voirie\Traits;

use Application\Form\Voirie\VoirieSaisieForm;

/**
 * Description of VoirieSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait VoirieSaisieFormAwareTrait
{
    protected ?VoirieSaisieForm $formVoirieVoirieSaisie = null;



    /**
     * @param VoirieSaisieForm $formVoirieVoirieSaisie
     *
     * @return self
     */
    public function setFormVoirieVoirieSaisie( VoirieSaisieForm $formVoirieVoirieSaisie )
    {
        $this->formVoirieVoirieSaisie = $formVoirieVoirieSaisie;

        return $this;
    }



    public function getFormVoirieVoirieSaisie(): ?VoirieSaisieForm
    {
        if (empty($this->formVoirieVoirieSaisie)){
            $this->formVoirieVoirieSaisie = \Application::$container->get('FormElementManager')->get(VoirieSaisieForm::class);
        }

        return $this->formVoirieVoirieSaisie;
    }
}