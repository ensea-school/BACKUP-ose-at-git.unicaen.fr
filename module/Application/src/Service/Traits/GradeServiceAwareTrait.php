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
    protected ?GradeService $serviceGrade = null;



    /**
     * @param GradeService $serviceGrade
     *
     * @return self
     */
    public function setServiceGrade( GradeService $serviceGrade )
    {
        $this->serviceGrade = $serviceGrade;

        return $this;
    }



    public function getServiceGrade(): ?GradeService
    {
        if (empty($this->serviceGrade)){
            $this->serviceGrade = \Application::$container->get(GradeService::class);
        }

        return $this->serviceGrade;
    }
}