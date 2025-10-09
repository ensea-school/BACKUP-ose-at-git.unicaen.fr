<?php

namespace Lieu\Form;

/**
 * Description of VoirieSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait VoirieSaisieFormAwareTrait
{
    protected ?VoirieSaisieForm $formVoirieSaisie = null;



    /**
     * @param VoirieSaisieForm $formVoirieSaisie
     *
     * @return self
     */
    public function setFormVoirieSaisie(?VoirieSaisieForm $formVoirieSaisie)
    {
        $this->formVoirieSaisie = $formVoirieSaisie;

        return $this;
    }



    public function getFormVoirieSaisie(): ?VoirieSaisieForm
    {
        if (!empty($this->formVoirieSaisie)) {
            return $this->formVoirieSaisie;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(VoirieSaisieForm::class);
    }
}