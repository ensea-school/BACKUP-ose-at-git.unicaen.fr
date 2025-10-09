<?php

namespace Paiement\Form\CentreCout;


/**
 * Description of CentreCoutSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutSaisieFormAwareTrait
{
    protected ?CentreCoutSaisieForm $formCentreCoutCentreCoutSaisie = null;



    /**
     * @param CentreCoutSaisieForm $formCentreCoutCentreCoutSaisie
     *
     * @return self
     */
    public function setFormCentreCoutCentreCoutSaisie(?CentreCoutSaisieForm $formCentreCoutCentreCoutSaisie)
    {
        $this->formCentreCoutCentreCoutSaisie = $formCentreCoutCentreCoutSaisie;

        return $this;
    }



    public function getFormCentreCoutCentreCoutSaisie(): ?CentreCoutSaisieForm
    {
        if (!empty($this->formCentreCoutCentreCoutSaisie)) {
            return $this->formCentreCoutCentreCoutSaisie;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(CentreCoutSaisieForm::class);
    }
}