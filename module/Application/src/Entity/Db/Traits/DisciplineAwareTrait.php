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
    protected ?Discipline $discipline;



    /**
     * @param Discipline|null $discipline
     *
     * @return self
     */
    public function setDiscipline( ?Discipline $discipline )
    {
        $this->discipline = $discipline;

        return $this;
    }



    public function getDiscipline(): ?Discipline
    {
        return $this->discipline;
    }
}