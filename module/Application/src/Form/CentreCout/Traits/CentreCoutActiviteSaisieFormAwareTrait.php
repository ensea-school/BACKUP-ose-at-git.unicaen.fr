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
    protected ?CentreCoutActiviteSaisieForm $formCentreCoutCentreCoutActiviteSaisie;



    /**
     * @param CentreCoutActiviteSaisieForm|null $formCentreCoutCentreCoutActiviteSaisie
     *
     * @return self
     */
    public function setFormCentreCoutCentreCoutActiviteSaisie( ?CentreCoutActiviteSaisieForm $formCentreCoutCentreCoutActiviteSaisie )
    {
        $this->formCentreCoutCentreCoutActiviteSaisie = $formCentreCoutCentreCoutActiviteSaisie;

        return $this;
    }



    public function getFormCentreCoutCentreCoutActiviteSaisie(): ?CentreCoutActiviteSaisieForm
    {
        if (!$this->formCentreCoutCentreCoutActiviteSaisie){
            $this->formCentreCoutCentreCoutActiviteSaisie = \Application::$container->get('FormElementManager')->get(CentreCoutActiviteSaisieForm::class);
        }

        return $this->formCentreCoutCentreCoutActiviteSaisie;
    }
}