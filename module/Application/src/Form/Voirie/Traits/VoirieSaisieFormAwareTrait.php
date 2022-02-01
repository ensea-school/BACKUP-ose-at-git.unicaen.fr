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
    protected ?VoirieSaisieForm $formVoirieVoirieSaisie;



    /**
     * @param VoirieSaisieForm|null $formVoirieVoirieSaisie
     *
     * @return self
     */
    public function setFormVoirieVoirieSaisie( ?VoirieSaisieForm $formVoirieVoirieSaisie )
    {
        $this->formVoirieVoirieSaisie = $formVoirieVoirieSaisie;

        return $this;
    }



    public function getFormVoirieVoirieSaisie(): ?VoirieSaisieForm
    {
        if (!$this->formVoirieVoirieSaisie){
            $this->formVoirieVoirieSaisie = \Application::$container->get('FormElementManager')->get(VoirieSaisieForm::class);
        }

        return $this->formVoirieVoirieSaisie;
    }
}