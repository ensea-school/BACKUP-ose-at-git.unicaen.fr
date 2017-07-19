<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Grade;

/**
 * Description of GradeAwareTrait
 *
 * @author UnicaenCode
 */
trait GradeAwareTrait
{
    /**
     * @var Grade
     */
    private $grade;





    /**
     * @param Grade $grade
     * @return self
     */
    public function setGrade( Grade $grade = null )
    {
        $this->grade = $grade;
        return $this;
    }



    /**
     * @return Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }
}