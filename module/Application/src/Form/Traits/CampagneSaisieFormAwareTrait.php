<?php

namespace Application\Form\Traits;

use Application\Form\CampagneSaisieForm;

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
    public function setFormCampagneSaisie( ?CampagneSaisieForm $formCampagneSaisie )
    {
        $this->formCampagneSaisie = $formCampagneSaisie;

        return $this;
    }



    public function getFormCampagneSaisie(): ?CampagneSaisieForm
    {
        if (empty($this->formCampagneSaisie)){
            $this->formCampagneSaisie = \Application::$container->get('FormElementManager')->get(CampagneSaisieForm::class);
        }

        return $this->formCampagneSaisie;
    }
}