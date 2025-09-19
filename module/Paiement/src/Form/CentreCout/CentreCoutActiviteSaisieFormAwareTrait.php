<?php

namespace Paiement\Form\CentreCout;


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

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(CentreCoutActiviteSaisieForm::class);
    }
}