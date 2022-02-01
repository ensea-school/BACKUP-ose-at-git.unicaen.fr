<?php

namespace Application\Form\Interfaces;

use Application\Form\DisciplineForm;

/**
 * Description of DisciplineFormAwareInterface
 *
 * @author UnicaenCode
 */
interface DisciplineFormAwareInterface
{
    /**
     * @param DisciplineForm|null $formDiscipline
     *
     * @return self
     */
    public function setFormDiscipline( ?DisciplineForm $formDiscipline );



    public function getFormDiscipline(): ?DisciplineForm;
}