<?php

namespace Intervenant\Form;


/**
 * Description of NoteSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait NoteSaisieFormAwareTrait
{
    protected ?NoteSaisieForm $formNoteSaisie = null;



    /**
     * @param NoteSaisieForm $formNoteSaisie
     *
     * @return self
     */
    public function setFormNoteSaisie(?NoteSaisieForm $formNoteSaisie)
    {
        $this->formNoteSaisie = $formNoteSaisie;

        return $this;
    }



    public function getFormNoteSaisie(): ?NoteSaisieForm
    {
        if (empty($this->formNoteSaisie)) {
            $this->formNoteSaisie = \Application::$container->get('FormElementManager')->get(NoteSaisieForm::class);
        }

        return $this->formNoteSaisie;
    }
}