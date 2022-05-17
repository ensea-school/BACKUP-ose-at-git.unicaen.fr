<?php

namespace Application\Form\CentreCout\Traits;

use Application\Form\CentreCout\CentreCoutActiviteSaisieForm;

/**
 * Description of CentreCoutActiviteSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutActiviteSaisieFormAwareTrait
{
    protected ?CentreCoutActiviteSaisieForm $formCentreCoutCentreCoutActiviteSaisie = null;



    /**
     * @param CentreCoutActiviteSaisieForm $formCentreCoutCentreCoutActiviteSaisie
     *
     * @return self
     */
    public function setFormCentreCoutCentreCoutActiviteSaisie(?CentreCoutActiviteSaisieForm $formCentreCoutCentreCoutActiviteSaisie)
    {
        $this->formCentreCoutCentreCoutActiviteSaisie = $formCentreCoutCentreCoutActiviteSaisie;

        return $this;
    }



    public function getFormCentreCoutCentreCoutActiviteSaisie(): ?CentreCoutActiviteSaisieForm
    {
        if (!empty($this->formCentreCoutCentreCoutActiviteSaisie)) {
            return $this->formCentreCoutCentreCoutActiviteSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(CentreCoutActiviteSaisieForm::class);
    }
}