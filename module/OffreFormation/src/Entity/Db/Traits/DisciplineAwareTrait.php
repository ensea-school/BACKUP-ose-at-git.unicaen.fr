<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\Discipline;

/**
 * Description of DisciplineAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineAwareTrait
{
    protected ?Discipline $discipline = null;



    /**
     * @param Discipline $discipline
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