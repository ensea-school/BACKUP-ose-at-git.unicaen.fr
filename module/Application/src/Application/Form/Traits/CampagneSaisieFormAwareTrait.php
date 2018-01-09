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
    /**
     * @var CampagneSaisieForm
     */
    private $formCampagneSaisie;



    /**
     * @param CampagneSaisieForm $formCampagneSaisie
     *
     * @return self
     */
    public function setFormCampagneSaisie(CampagneSaisieForm $formCampagneSaisie)
    {
        $this->formCampagneSaisie = $formCampagneSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return CampagneSaisieForm
     */
    public function getFormCampagneSaisie()
    {
        if (!empty($this->formCampagneSaisie)) {
            return $this->formCampagneSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(CampagneSaisieForm::class);
    }
}