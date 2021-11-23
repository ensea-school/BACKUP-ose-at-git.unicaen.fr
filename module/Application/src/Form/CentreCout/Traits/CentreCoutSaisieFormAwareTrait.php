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
    /**
     * @var CentreCoutSaisieForm
     */
    private $formCentreCoutSaisie;



    /**
     * @param CentreCoutSaisieForm $formCentreCoutSaisie
     *
     * @return self
     */
    public function setFormCentreCoutSaisie(CentreCoutSaisieForm $formCentreCoutSaisie)
    {
        $this->formCentreCoutSaisie = $formCentreCoutSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return CentreCoutSaisieForm
     */
    public function getFormCentreCoutSaisie()
    {
        if (!empty($this->formCentreCoutSaisie)) {
            return $this->formCentreCoutSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(CentreCoutSaisieForm::class);
    }
}
