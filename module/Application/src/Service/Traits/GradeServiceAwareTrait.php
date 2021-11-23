<?php

namespace Application\Service\Traits;

use Application\Service\GradeService;

/**
 * Description of GradeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait GradeServiceAwareTrait
{
    /**
     * @var GradeService
     */
    private $serviceGrade;



    /**
     * @param GradeService $serviceGrade
     *
     * @return self
     */
    public function setServiceGrade(GradeService $serviceGrade)
    {
        $this->serviceGrade = $serviceGrade;

        return $this;
    }



    /**
     * @return GradeService
     */
    public function getServiceGrade()
    {
        if (empty($this->serviceGrade)) {
            $this->serviceGrade = \Application::$container->get(GradeService::class);
        }

        return $this->serviceGrade;
    }
}