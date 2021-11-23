<?php

namespace Application\Form\Interfaces;

use Application\Form\DisciplineForm;
use RuntimeException;

/**
 * Description of DisciplineFormAwareInterface
 *
 * @author UnicaenCode
 */
interface DisciplineFormAwareInterface
{
    /**
     * @param DisciplineForm $formDiscipline
     * @return self
     */
    public function setFormDiscipline( DisciplineForm $formDiscipline );



    /**
     * @return DisciplineFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormDiscipline();
}