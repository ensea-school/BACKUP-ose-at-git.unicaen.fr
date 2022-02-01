<?php

namespace Application\Form\Traits;

use Application\Form\DisciplineForm;

/**
 * Description of DisciplineFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineFormAwareTrait
{
    protected ?DisciplineForm $formDiscipline;



    /**
     * @param DisciplineForm|null $formDiscipline
     *
     * @return self
     */
    public function setFormDiscipline( ?DisciplineForm $formDiscipline )
    {
        $this->formDiscipline = $formDiscipline;

        return $this;
    }



    public function getFormDiscipline(): ?DisciplineForm
    {
        if (!$this->formDiscipline){
            $this->formDiscipline = \Application::$container->get('FormElementManager')->get(DisciplineForm::class);
        }

        return $this->formDiscipline;
    }
}