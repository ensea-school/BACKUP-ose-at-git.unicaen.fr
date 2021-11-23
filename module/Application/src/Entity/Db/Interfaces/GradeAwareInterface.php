<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Grade;

/**
 * Description of GradeAwareInterface
 *
 * @author UnicaenCode
 */
interface GradeAwareInterface
{
    /**
     * @param Grade $grade
     * @return self
     */
    public function setGrade( Grade $grade = null );



    /**
     * @return Grade
     */
    public function getGrade();
}