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
    protected ?CentreCoutStructureSaisieForm $formCentreCoutCentreCoutStructureSaisie = null;



    /**
     * @param CentreCoutStructureSaisieForm $formCentreCoutCentreCoutStructureSaisie
     *
     * @return self
     */
    public function setFormCentreCoutCentreCoutStructureSaisie( ?CentreCoutStructureSaisieForm $formCentreCoutCentreCoutStructureSaisie )
    {
        $this->formCentreCoutCentreCoutStructureSaisie = $formCentreCoutCentreCoutStructureSaisie;

        return $this;
    }



    public function getFormCentreCoutCentreCoutStructureSaisie(): ?CentreCoutStructureSaisieForm
    {
        if (empty($this->formCentreCoutCentreCoutStructureSaisie)){
            $this->formCentreCoutCentreCoutStructureSaisie = \Application::$container->get('FormElementManager')->get(CentreCoutStructureSaisieForm::class);
        }

        return $this->formCentreCoutCentreCoutStructureSaisie;
    }
}