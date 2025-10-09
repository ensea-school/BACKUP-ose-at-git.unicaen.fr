<?php

namespace Lieu\Form;

/**
 * Description of DepartementSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementSaisieFormAwareTrait
{
    protected ?DepartementSaisieForm $formDepartementSaisie = null;



    /**
     * @param DepartementSaisieForm $formDepartementSaisie
     *
     * @return self
     */
    public function setFormDepartementSaisie(?DepartementSaisieForm $formDepartementSaisie)
    {
        $this->formDepartementSaisie = $formDepartementSaisie;

        return $this;
    }



    public function getFormDepartementSaisie(): ?DepartementSaisieForm
    {
        if (!empty($this->formDepartementSaisie)) {
            return $this->formDepartementSaisie;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(DepartementSaisieForm::class);
    }
}