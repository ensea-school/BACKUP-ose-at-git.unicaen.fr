<?php

namespace Application\Form\Departement\Traits;

use Application\Form\Departement\DepartementSaisieForm;

/**
 * Description of DepartementSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementSaisieFormAwareTrait
{
    protected ?DepartementSaisieForm $formDepartementDepartementSaisie = null;



    /**
     * @param DepartementSaisieForm $formDepartementDepartementSaisie
     *
     * @return self
     */
    public function setFormDepartementDepartementSaisie(?DepartementSaisieForm $formDepartementDepartementSaisie)
    {
        $this->formDepartementDepartementSaisie = $formDepartementDepartementSaisie;

        return $this;
    }



    public function getFormDepartementDepartementSaisie(): ?DepartementSaisieForm
    {
        if (!empty($this->formDepartementDepartementSaisie)) {
            return $this->formDepartementDepartementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(DepartementSaisieForm::class);
    }
}