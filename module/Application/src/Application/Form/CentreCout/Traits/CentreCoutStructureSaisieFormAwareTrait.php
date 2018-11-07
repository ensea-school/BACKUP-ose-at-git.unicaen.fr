<?php

namespace Application\Form\CentreCout\Traits;

use Application\Form\CentreCout\CentreCoutStructureSaisieForm;

/**
 * Description of CentreCoutStructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutStructureSaisieFormAwareTrait
{
    /**
     * @var CentreCoutStructureSaisieForm
     */
    private $formCentreCoutStructureSaisie;



    /**
     * @param CentreCoutStructureSaisieForm $formCentreCoutStructureSaisie
     *
     * @return self
     */
    public function setFormCentreCoutStructureSaisie(CentreCoutSructureSaisieForm $formCentreCoutStructureSaisie)
    {
        $this->formCentreCoutStructureSaisie = $formCentreCoutStructureSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return CentreCoutStructureSaisieForm
     */
    public function getFormCentreCoutStructureSaisie()
    {
        if (!empty($this->formCentreCoutStructureSaisie)) {
            return $this->formCentreCoutStructureSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(CentreCoutStructureSaisieForm::class);
    }
}
