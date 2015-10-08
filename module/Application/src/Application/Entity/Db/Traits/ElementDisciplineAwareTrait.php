<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ElementDiscipline;

/**
 * Description of ElementDisciplineAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementDisciplineAwareTrait
{
    /**
     * @var ElementDiscipline
     */
    private $elementDiscipline;





    /**
     * @param ElementDiscipline $elementDiscipline
     * @return self
     */
    public function setElementDiscipline( ElementDiscipline $elementDiscipline = null )
    {
        $this->elementDiscipline = $elementDiscipline;
        return $this;
    }



    /**
     * @return ElementDiscipline
     */
    public function getElementDiscipline()
    {
        return $this->elementDiscipline;
    }
}