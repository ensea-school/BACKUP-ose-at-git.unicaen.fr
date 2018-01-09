<?php

namespace Application\Service\Traits;

use Application\Service\TypeInterventionService;

/**
 * Description of TypeInterventionAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionServiceAwareTrait
{
    /**
     * @var TypeInterventionService
     */
    private $serviceTypeIntervention;



    /**
     * @param TypeInterventionService $serviceTypeIntervention
     *
     * @return self
     */
    public function setServiceTypeIntervention(TypeInterventionService $serviceTypeIntervention)
    {
        $this->serviceTypeIntervention = $serviceTypeIntervention;

        return $this;
    }



    /**
     * @return TypeInterventionService
     */
    public function getServiceTypeIntervention()
    {
        if (empty($this->serviceTypeIntervention)) {
            $this->serviceTypeIntervention = \Application::$container->get(TypeInterventionService::class);
        }

        return $this->serviceTypeIntervention;
    }
}