<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Discipline;

/**
 * Description of DisciplineAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineAwareTrait
{
    /**
     * @var Discipline
     */
    private $discipline;





    /**
     * @param Discipline $discipline
     * @return self
     */
    public function setDiscipline( Discipline $discipline = null )
    {
        $this->discipline = $discipline;
        return $this;
    }



    /**
     * @return Discipline
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }
}