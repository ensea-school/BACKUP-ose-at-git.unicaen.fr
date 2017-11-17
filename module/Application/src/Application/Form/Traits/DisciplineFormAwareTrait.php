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
    /**
     * @var DisciplineForm
     */
    private $formDiscipline;



    /**
     * @param DisciplineForm $formDiscipline
     *
     * @return self
     */
    public function setFormDiscipline(DisciplineForm $formDiscipline)
    {
        $this->formDiscipline = $formDiscipline;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DisciplineForm
     */
    public function getFormDiscipline()
    {
        if (!empty($this->formDiscipline)) {
            return $this->formDiscipline;
        }

        return \Application::$container->get('FormElementManager')->get('DisciplineForm');
    }
}