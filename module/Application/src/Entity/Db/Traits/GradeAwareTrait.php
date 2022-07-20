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
    protected ?Grade $grade = null;



    /**
     * @param Grade $grade
     *
     * @return self
     */
    public function setGrade( ?Grade $grade )
    {
        $this->grade = $grade;

        return $this;
    }



    public function getGrade(): ?Grade
    {
        return $this->grade;
    }
}