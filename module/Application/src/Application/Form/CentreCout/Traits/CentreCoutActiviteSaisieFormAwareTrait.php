<?php

namespace Application\Form\CentreCout\Traits;

use Application\Form\CentreCout\CentreCoutActiviteSaisieForm;

trait CentreCoutActiviteSaisieFormAwareTrait
{
    /**
     * @var CentreCoutActiviteSaisieForm
     */
    private $formCentreCoutActiviteSaisie;



    /**
     * @param CentreCoutSaisieForm $formCentreCoutActiviteSaisie
     *
     * @return self
     */
    public function setFormCentreCoutActiviteSaisie(CentreCoutActiviteSaisieForm $formCentreCoutActiviteSaisie)
    {
        $this->formCentreCoutActiviteSaisie = $formCentreCoutActiviteSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return CentreCoutActiviteSaisieForm
     */
    public function getFormCentreCoutActiviteSaisie()
    {
        if (!empty($this->formCentreCoutActiviteSaisie)) {
            return $this->formCentreCoutActiviteSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(CentreCoutActiviteSaisieForm::class);
    }
}
