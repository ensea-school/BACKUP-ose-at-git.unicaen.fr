<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\ElementDiscipline;

/**
 * Description of ElementDisciplineAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementDisciplineAwareInterface
{
    /**
     * @param ElementDiscipline $elementDiscipline
     * @return self
     */
    public function setElementDiscipline( ElementDiscipline $elementDiscipline = null );



    /**
     * @return ElementDiscipline
     */
    public function getElementDiscipline();
}