<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\DisciplineForm;

/**
 * Description of DisciplineFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineFormAwareTrait
{
    protected ?DisciplineForm $formDiscipline = null;



    /**
     * @param DisciplineForm $formDiscipline
     *
     * @return self
     */
    public function setFormDiscipline(?DisciplineForm $formDiscipline)
    {
        $this->formDiscipline = $formDiscipline;

        return $this;
    }



    public function getFormDiscipline(): ?DisciplineForm
    {
        if (!empty($this->formDiscipline)) {
            return $this->formDiscipline;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(DisciplineForm::class);
    }
}