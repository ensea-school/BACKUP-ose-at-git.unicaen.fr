<?php

namespace Service\Form;

/**
 * Description of CampagneSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CampagneSaisieFormAwareTrait
{
    protected ?CampagneSaisieForm $formCampagneSaisie = null;



    /**
     * @param CampagneSaisieForm $formCampagneSaisie
     *
     * @return self
     */
    public function setFormCampagneSaisie(?CampagneSaisieForm $formCampagneSaisie)
    {
        $this->formCampagneSaisie = $formCampagneSaisie;

        return $this;
    }



    public function getFormCampagneSaisie(): ?CampagneSaisieForm
    {
        if (!empty($this->formCampagneSaisie)) {
            return $this->formCampagneSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(CampagneSaisieForm::class);
    }
}