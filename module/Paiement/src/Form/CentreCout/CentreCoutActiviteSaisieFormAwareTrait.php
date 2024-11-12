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

        return \AppAdmin::container()->get('FormElementManager')->get(CentreCoutActiviteSaisieForm::class);
    }
}