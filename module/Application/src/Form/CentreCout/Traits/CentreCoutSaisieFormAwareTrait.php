<?php

namespace Application\Form\CentreCout\Traits;

use Application\Form\CentreCout\CentreCoutSaisieForm;

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

        return \Application::$container->get('FormElementManager')->get(CentreCoutSaisieForm::class);
    }
}