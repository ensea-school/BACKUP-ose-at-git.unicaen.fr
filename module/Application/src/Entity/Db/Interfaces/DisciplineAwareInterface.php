<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Discipline;

/**
 * Description of DisciplineAwareInterface
 *
 * @author UnicaenCode
 */
interface DisciplineAwareInterface
{
    /**
     * @param Discipline $discipline
     * @return self
     */
    public function setDiscipline( Discipline $discipline = null );



    /**
     * @return Discipline
     */
    public function getDiscipline();
}