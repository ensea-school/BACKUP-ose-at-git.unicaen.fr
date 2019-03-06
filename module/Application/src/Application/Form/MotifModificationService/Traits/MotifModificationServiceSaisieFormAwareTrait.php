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
    /**
     * @var MotifModificationServiceSaisieForm
     */
    private $formMotifModificationServiceSaisie;



    /**
     * @param MotifModificationServiceSaisieForm $formMotifModificationServiceSaisie
     *
     * @return self
     */
    public function setFormMotifModificationServiceSaisie(MotifModificationServiceSaisieForm $formMotifModificationServiceSaisie)
    {
        $this->formMotifModificationServiceSaisie = $formMotifModificationServiceSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return MotifModificationServiceSaisieForm
     */
    public function getFormMotifModificationServiceSaisie()
    {
        if (!empty($this->formMotifModificationServiceSaisie)) {
            return $this->formMotifModificationServiceSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(MotifModificationServiceSaisieForm::class);
    }
}
